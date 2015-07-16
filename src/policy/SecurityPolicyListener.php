<?php

class SecurityPolicyEventListener
  extends PhutilEventListener {

  public function register() {
    $this->listen(PhabricatorEventType::TYPE_MANIPHEST_WILLEDITTASK);
    //$this->listen(PhabricatorEventType::TYPE_MANIPHEST_DIDEDITTASK);
  }

  public function handleEvent(PhutilEvent $event) {
    switch ($event->getType()) {
      case PhabricatorEventType::TYPE_MANIPHEST_WILLEDITTASK:
        return $this->willEditTask($event);
      //case PhabricatorEventType::TYPE_MANIPHEST_DIDEDITTASK:
      //  return $this->didEditTask($event);
    }
  }

  private function willEditTask($event) {
    $task         = $event->getValue('task');
    $transactions = $event->getValue('transactions');
    $is_new       = $event->getValue('new');
    $type_viewpol = PhabricatorTransactions::TYPE_VIEW_POLICY;
    $type_editpol = PhabricatorTransactions::TYPE_EDIT_POLICY;
    $type_edge    = PhabricatorTransactions::TYPE_EDGE;
    $type_hasproj = PhabricatorProjectObjectHasProjectEdgeType::EDGECONST;
    $security_setting = WMFSecurityPolicy::getSecurityFieldValue($task);

    if (!$is_new) {
      // for pre-existing tasks, do nothing here, the custom herald action will
      // enforce the policy (see SecurityPolicyEnforcerAction.php)
      return;
    }

    if ($is_new && $security_setting == 'default') {
      return;
    }

    if ($project = WMFSecurityPolicy::getSecurityProjectForTask($task)) {
      $project_phids = array($project->getPHID() => $project->getPHID());
    } else {
      $project_phids = array();
    }

    if ($is_new && $security_setting == 'ops-access-request') {
      // ops access requests don't modify the request task, instead
      // we create a subtask which gets custom policy settings applied.
      // any returned transactions get applied to the parent task to record
      // the association with the subtask.
      $subtask_trns = WMFSecurityPolicy::createPrivateSubtask($task);
      if (!empty($subtask_trns) && !empty($project_phids)) {
        $trans[$type_edge] = id(new ManiphestTransaction())
            ->setTransactionType($type_edge)
            ->setMetadataValue('edge:type',$type_hasproj)
            ->setNewValue(array('+' => $project_phids));
      }
    } else {
      // other secure tasks get standard policies applied
      // if it's a security-bug then we include subscribers (CCs)
      $include_subscribers = ($security_setting == 'security-bug');

      $edit_policy = WMFSecurityPolicy::createCustomPolicy(
        $task,
        $task->getAuthorPHID(),
        $project_phids,
        $include_subscribers
      );

      // view policy and edit policy will be identical:
      $policy_phid = $edit_policy->getPHID();

      $trans = array();

      if (!empty($project_phids)) {
        $trans[$type_edge] = id(new ManiphestTransaction())
            ->setTransactionType($type_edge)
            ->setMetadataValue('edge:type',$type_hasproj)
            ->setNewValue(array('+' => $project_phids));
      }

      $trans[$type_viewpol] = id(new ManiphestTransaction())
          ->setTransactionType($type_viewpol)
          ->setNewValue($policy_phid);

      $trans[$type_editpol] = id(new ManiphestTransaction())
          ->setTransactionType($type_editpol)
          ->setNewValue($policy_phid);

      // These transactions replace any user-generated transactions of
      // the same type, e.g. user-supplied policy gets overwritten
      // with custom policy.
      foreach($transactions as $n => $t) {
        $type = $t->getTransactionType();
        if ($type == $type_edge) {
          if ($t->getMetadataValue('edge:type') == $type_hasproj) {
            $val = $t->getNewValue();
            if (isset($val['=']) && is_array($val['='])) {
              $val['='] = array_unique(
                            array_merge(
                              $val['='], $project_phids));
            } else {
              $val['+'] = $project_phids;
            }
            $t->setNewValue($val);
            unset($trans[$type_edge]);
          }
        }
        else if (isset($trans[$type])) {
          $transactions[$n] = $trans[$type];
          unset($trans[$type]);
        }
      }

      $event->setValue('transactions', $transactions);
    }

    if (!empty($trans)) {
      // apply remaining transactions
      $content_source = PhabricatorContentSource::newForSource(
        PhabricatorContentSource::SOURCE_UNKNOWN,
        array());

      $acting_as = id(new PhabricatorManiphestApplication())
          ->getPHID();

      id(new ManiphestTransactionEditor())
        ->setActor(PhabricatorUser::getOmnipotentUser())
        ->setActingAsPHID($acting_as)
        ->setContentSource($content_source)
        ->applyTransactions($task, $trans);
    }
  }
}