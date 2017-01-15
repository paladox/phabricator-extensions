<?php

/**
 * This file is automatically generated. Use 'arc liberate' to rebuild it.
 *
 * @generated
 * @phutil-library-version 2
 */
phutil_register_library_map(array(
  '__library_version__' => 2,
  'class' => array(
    'CreatePolicyConduitAPIMethod' => 'src/conduit/CreatePolicyConduitAPIMethod.php',
    'CustomGithubDownloadLinks' => 'src/diffusion/CustomGithubDownloadLinks.php',
    'CustomLoginHandler' => 'src/other/CustomLoginHandler.php',
    'DifferentialApplyPatchWithOnlyGitField' => 'src/customfields/DifferentialApplyPatchWithOnlyGitField.php',
    'GerritApplication' => 'src/gerrit/GerritApplication.php',
    'GerritChangeIdField' => 'src/gerrit/GerritChangeIdField.php',
    'GerritProjectController' => 'src/gerrit/GerritProjectController.php',
    'GerritProjectListController' => 'src/gerrit/GerritProjectListController.php',
    'GerritProjectMap' => 'src/gerrit/GerritProjectMap.php',
    'LDAPUserQueryConduitAPIMethod' => 'src/conduit/LDAPUserQueryConduitAPIMethod.php',
    'LDAPUserpageCustomField' => 'src/customfields/LDAPUserpageCustomField.php',
    'MediaWikiUserQueryConduitAPIMethod' => 'src/conduit/MediaWikiUserQueryConduitAPIMethod.php',
    'MediaWikiUserpageCustomField' => 'src/customfields/MediaWikiUserpageCustomField.php',
    'PhabricatorMediaWikiAuthProvider' => 'src/oauth/PhabricatorMediaWikiAuthProvider.php',
    'PhabricatorMilestoneNavProfileMenuItem' => 'src/panel/PhabricatorMilestoneNavProfileMenuItem.php',
    'PhutilMediaWikiAuthAdapter' => 'src/oauth/PhutilMediaWikiAuthAdapter.php',
    'PolicyQueryConduitAPIMethod' => 'src/conduit/PolicyQueryConduitAPIMethod.php',
    'ProjectOpenTasksProfilePanel' => 'src/panel/ProjectOpenTasksProfilePanel.php',
    'WmfConfigSource' => 'src/other/WmfConfigSource.php',
  ),
  'function' => array(),
  'xmap' => array(
    'CreatePolicyConduitAPIMethod' => 'ConduitAPIMethod',
    'CustomLoginHandler' => 'PhabricatorAuthLoginHandler',
    'DifferentialApplyPatchWithOnlyGitField' => 'DifferentialCustomField',
    'GerritApplication' => 'PhabricatorApplication',
    'GerritChangeIdField' => 'PhabricatorCommitCustomField',
    'GerritProjectController' => 'PhabricatorController',
    'GerritProjectListController' => 'GerritProjectController',
    'LDAPUserQueryConduitAPIMethod' => 'UserConduitAPIMethod',
    'LDAPUserpageCustomField' => 'PhabricatorUserCustomField',
    'MediaWikiUserQueryConduitAPIMethod' => 'UserConduitAPIMethod',
    'MediaWikiUserpageCustomField' => 'PhabricatorUserCustomField',
    'PhabricatorMediaWikiAuthProvider' => 'PhabricatorOAuth1AuthProvider',
    'PhabricatorMilestoneNavProfileMenuItem' => 'PhabricatorProfileMenuItem',
    'PhutilMediaWikiAuthAdapter' => 'PhutilOAuth1AuthAdapter',
    'PolicyQueryConduitAPIMethod' => 'ConduitAPIMethod',
    'ProjectOpenTasksProfilePanel' => 'PhabricatorProfilePanel',
    'WmfConfigSource' => 'PhabricatorConfigSiteSource',
  ),
));
