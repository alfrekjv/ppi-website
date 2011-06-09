<?php
class APP_Controller_Admin extends APP_Controller_Application {

	protected $name = null;

	function __construct() {

		parent::__construct();

        // Admins only viewing this screen
        $this->adminLoginCheck();

		if(!$this->isAdminLoggedIn()) {
			throw new PPI_Exception('Cheatin\' eh ?');
		}

	}

	/**
	 * Admin dashboard
	 *
	 */
	function index() {
		 $this->adminLoad('admin/dashboard', array(
            'leftMenu' => true,
            'pageTitle' => 'Admin Dashboard'
        ));
	}


    /**
     * Email template CRUD handler
     *
     */
    function emailtemplate() {

        $sMode = $this->oInput->get('emailtemplate');
        switch($sMode) {
            case 'create':
            case 'edit':
                $this->etAddEdit($sMode);
                break;

            case 'delete':
                $this->etDelete();
                break;

            case 'view':
                $this->etView();
                break;

            case 'list':
            default:
                $this->etList();
                break;
        }

    }

    /**
     * Email log CRUD handler
     *
     */
	function emaillog() {


		$sMode = $this->oInput->get('emaillog');
		switch($sMode) {

			case 'delete':
				$this->elDelete();
				break;

			case 'view':
				$this->elView();
				break;

			case 'list':
			default:
				$this->elList();
				break;
		}

	}

    /**
     * AdminController::user()
     * Main method that chooses the appropriate method to handle the page request
     * @return void
     */
    function user() {

        $sMode = $this->get('user');
        switch($sMode) {

        	case 'roles':
        		$this->roleHandler($this->get('roles', 'list'));
        		break;

        	case 'updatepassword':
        		$this->userUpdatePassword();
        		break;

            case 'create':
            case 'edit':
                $this->userAddEdit($sMode);
                break;

            case 'delete':
                $this->userDelete();
                break;

            case 'view':
                $this->userView();
                break;

            case 'list':
            default:
                $this->userList();
                break;
        }

    }


    /**
     * Admin Page CRUD
     * Main method that chooses the appropriate method to handle the page request
     * @return void
     */
    function blog() {

        $sMode = $this->get(__FUNCTION__, 'list');
        switch($sMode) {

            case 'create':
            case 'edit':
                $this->blogAddEdit($sMode);
                break;

            case 'delete':
                $this->blogDelete($this->get($sMode));
                break;

            case 'view':
            case 'list':
            default:
                $this->blogList();
                break;
        }

    }

    /**
     * Show the list of blogs
     *
     */
    function blogList() {

		$oBlog = new APP_Model_Blog();

		// Get the pages
		$posts = $oBlog->getPosts();

		// Load the template.
		$this->adminLoad('admin/blog_list', compact('posts'));

    }

    /**
     * AdminController::pageAddEdit()
     * Add or Edit a page
     * @return void
     */
    protected function blogAddEdit($p_sMode = 'create') {

        $oBlog    = new APP_Model_Blog();
        $bEdit     = ($p_sMode === 'edit');
        $oForm     = new PPI_Model_Form();
    	$checkCode = false;
    	$iPageID  = $this->get($p_sMode, 0);

        $oForm->init('admin_blog_addedit');

        $oForm->setFormStructure($oBlog->getAddEditFormStructure($p_sMode));

        if($oForm->isSubmitted() && $oForm->isValidated()) {

            $aSubmitValues = $oForm->getSubmitValues();
            // Edit mode to set the primary key so that it performs an update
            if($bEdit && $iPageID > 0) {
                $aSubmitValues[$oBlog->getPrimaryKey()] = $iPageID;
            }

        	// We're in add mode lets make sure this permalink doesn't already exist
        	if(!$bEdit) {
				if( count($oBlog->getRecord('permalink = ' . $oBlog->quote($aSubmitValues['permalink']))) > 0) {
					$oForm->setElementError('permalink', 'That permalink already exists');
				}

        	// We're in edit mode, but we still need to see if they have changed the 'permalink'
        	} else {

        		// Grab the existing DB info
        		if( count($aPage = $oBlog->getRecord('id = ' . $iPageID)) > 0) {

        			// Compare The DB info against the submitted into.
					// If they're different then we need to make sure this doesn't exist elsewhere.
        			if($aPage['permalink'] != $aSubmitValues['permalink']) {
        				// Lets see if this modified code exists elsewhere.
        				if(count($oBlog->getRecord('permalink = ' . $oBlog->quote($aSubmitValues['permalink']))) > 0) {
        					$oForm->setElementError('permalink', 'That code already exists');
        				}
        			}
        		}
        	}

        	if($oForm->isValidated()) {

            	// Put the record (insert/update)
            	$aSubmitValues['user_id'] = $this->getAuthData(false)->id;
            	$oBlog->putRecord($aSubmitValues);
            	$this->setFlashMessage('Blog successfully ' . ($bEdit ? 'updated' : 'created') . '.');
        	    $this->redirect('admin/blog/list');
        	}
        }

        if($bEdit === true) {
            if( ($iBlogID = $this->get('edit', 0)) < 1) {
                throw new PPI_Exception('Invalid Blog Post ID: ' . $iBlogID);
            }
            // Set the defaults here
            $oForm->setDefaults($oBlog->find($iPageID));
        }
        $aViewVars     = array(
            'bEdit'       => $bEdit,
            'formBuilder' => $oForm->getRenderInformation(),  // FB Infos
            'leftMenu'    => true
        );
        $this->adminLoad('admin/blog_addedit', $aViewVars);

    }

    function blogDelete($p_iBlogID) {
        $oBlog = new APP_Model_Blog();
	$oBlog->delete($p_iBlogID);
	$this->setFlashMessage('Post successfully deleted');
	$this->redirect('admin/blog');
    }



    /**
     * User Role CRUD Handler
     *
     * @param string $action
     */
    protected function roleHandler($action) {
    	if($action === '') {
    		$action = 'list';
    	}
		$bReadOnly = true;
    	/**
    	 * Here we check if we're doing an operation that requires manipulation (eg: not "list").
    	 * If the db value user.roleMappingService is not set to database, we can't manipulate it
    	 * thus we show the "list" page with a flash message informing the user of this.
    	 */
		if($action != 'list' &&
           (!isset($this->getConfig()->user->roleMappingService) || $this->getConfig()->user->roleMappingService != 'database')) {

           	$bReadOnly = false;
			$this->setFlashMessage('Unable to perform this operation. RoleMapping is read-only from this area.', false);
			$this->redirect('admin/user/roles');
		}
    	switch($action) {

    		case 'create':
			case 'edit':
				$oRole = new APP_Model_User_Role();
    			break;

			case 'delete':
				$this->userRoleDelete();
				break;

			case 'list':
			default:
				$this->userRoleList(compact($bReadOnly));
				break;

    	}

    }

    /**
     * Delete a user role
     *
     */
    protected function userRoleDelete() {
		$oRole   = new APP_Model_User_Role();
		$oUser   = new APP_Model_User();
		$iRoleID = $this->get('delete');
		if(count( ($oUser->getList('role_id = ' . $oUser->quote($iRoleID))) ) > 0) {
			$this->setFlashMessage('Unable to delete this role is already in use.', false);
			$this->redirect('admin/user/roles');
		}
		$oRole->delete($this->get('delete'));
		$this->setFlashMessage('Role successfully deleted');
		$this->redirect('admin/user/roles');
    }

    /**
     * Display a user role list
     *
     */
    protected function userRoleList(array $p_aOptions = array()) {
    	$bReadOnly = isset($p_aOptions['bReadOnly']) && $p_aOptions['bReadOnly'] === true;
		$oRole = new APP_Model_User_Role();
		$aRoles = PPI_Helper_User::getRoles();
		$aRoleCounts = $oRole->getRoleCounts();
		$this->adminLoad('admin/roles_list', compact('aRoles', 'bReadOnly', 'aRoleCounts'));
    }


/**
     * AdminController::userList()
     * List all the users
     * @return void
     */
    protected function etList() {
        $oEmail = new PPI_Model_Email_Template();
        $this->adminLoad('admin/et_list', array(
            'emails'    => $oEmail->getList(),
            'leftMenu'  => true,
            'pageTitle' => 'Email Templates'
        ));
    }


    /**
     * AdminController::userAddEdit()
     * Add or Edit a user
     * @return void
     */
    protected function etAddEdit($p_sMode = 'create') {
        $oEmail    = new PPI_Model_Email_Template();
        $bEdit     = ($p_sMode == 'edit');
        $oForm     = new PPI_Model_Form();
    	$checkCode = false;
    	$iEmailID  = $this->get($p_sMode, 0);
        $oForm->init('admin_emailtemplate_addedit');
        $oForm->setFormStructure($oEmail->getAddEditFormStructure($p_sMode));
        if($oForm->isSubmitted() && $oForm->isValidated()) {
            $aSubmitValues = $oForm->getSubmitValues();
            // Edit mode to set the primary key so that it performs an update
            if($bEdit && $iEmailID > 0) {
                $aSubmitValues[$oEmail->getPrimaryKey()] = $iEmailID;
            }
        	// We're in add mode lets make sure this code doesn't already exist
        	if(!$bEdit) {
				if( count($oEmail->getRecord('code = ' . $oEmail->quote($aSubmitValues['code']))) > 0) {
					$oForm->setElementError('code', 'That code already exists');
				}

        	// We're in edit mode, but we still need to see if they have changed the 'code'
        	} else {
        		// Grab the existing DB info
        		if( count($aEmail = $oEmail->getRecord('id = ' . $iEmailID)) > 0) {
        			// Compare The DB info against the submitted into.
					// If they're different then we need to make sure this doesn't exist elsewhere.
        			if($aEmail['code'] != $aSubmitValues['code']) {
        				// Lets see if this modified code exists elsewhere.
        				if(count($aExistingEmail = $oEmail->getRecord('code = ' . $oEmail->quote($aSubmitValues['code']))) > 0) {
        					$oForm->setElementError('code', 'That code already exists');
        				}
        			}
        		}
        	}

        	if($oForm->isValidated()) {
            	// Put the record (insert/update)
            	$oEmail->putRecord($aSubmitValues);
            	$this->setFlashMessage('Email template successfully ' . ($bEdit ? 'updated' : 'created') . '.');
        	    $this->redirect('admin/emailtemplate/list');
        	}
        }

        if($bEdit === true) {
            if( ($iEmailID = $this->get('edit', 0)) < 1) {
                throw new PPI_Exception('Invalid Template ID: ' . $iEmailID);
            }
            // Set the defaults here
            $oForm->setDefaults($oEmail->find($iEmailID));
        }
        $aViewVars     = array(
            'bEdit'       => $bEdit,
            'formBuilder' => $oForm->getRenderInformation(),  // FB Infos
            'leftMenu'    => true
        );
        $this->adminLoad('admin/et_addedit', $aViewVars);

    }

    /**
     * AdminController::userView()
     * View a specific user
     * @return void
     */
    protected function etView() {
        if(($iEmailID = $this->get('view')) != '') {
            $oEmail = new PPI_Model_Email_Template();
            $this->adminLoad('admin/et_view', array(
                'email'      => $oEmail->find($iEmailID),
                'leftMenu'  => true,
                'pageTitle' => 'Edit Email Template'
            ));
        }
    }


    /**
     * AdminController::userDelete()
     * Delete a user
     * @return void
     */
    protected function etDelete() {
        if( ($iEmailID = $this->get('delete', 0)) < 1) {
            throw new PPI_Exception('Invalid User ID: ' . $iEmailID);
        }

        $oEmail = new PPI_Model_Email_Template();
        $oEmail->delete($iEmailID);
        $this->setFlashMessage('Email template successfully deleted.');
        $this->redirect('admin/emailtemplate/list');
    }


	/**
	 * AdminController::config()
	 * @todo Look into array_walk_recusrive to convert arrays to strings
	 * List all the users
	 * @return void
	 */
	function config() {

		$aConfig = $this->getConfig()->toArray();
		// Override to filer the results
		if( ($configKey = $this->get('config', '')) !== '' && array_key_exists($configKey, $aConfig)) {
			foreach($aConfig as $key => $val) {
				if($key !== $configKey) {
					unset($aConfig[$key]);
					continue;
				}
				foreach($aConfig[$key] as $subkey => $value) {
					if(is_array($value) && is_array($value[min(array_keys($value))])) {
						$aConfig[$key][$subkey] = implode(', ', $value[min(array_keys($value))]);
					}
				}
			}
		} else {
			foreach($aConfig as $key => $val) {

				foreach($aConfig[$key] as $subkey => $value) {
					if(is_array($value) && is_array($value[min(array_keys($value))])) {
						$aConfig[$key][$subkey] = implode(', ', $value[min(array_keys($value))]);
					}
				}

			}
		}

		$this->adminLoad('admin/config_list', array(
			'aConfig'   => $aConfig,
		    'leftMenu'  => true,
		    'pageTitle' => 'Configuration'
		));
	}

	/**
	 * Email log delete
	 *
	 */
	protected function elDelete() {
		if( ($iLogID = $this->get('delete', 0)) < 1) {
			throw new PPI_Exception('Invalid Log ID: ' . $iLogID);
		}
		$oLog = new PPI_Model_Shared('ppi_email_log', 'id');
		$oLog->delete($iLogID);
		$this->setFlashMessage('Log item successfully deleted.');
		$this->redirect('admin/emaillog');
	}

	/**
	 * Email log view
	 *
	 */
	protected function elView() {

		if( ($iLogID = $this->get('view', 0)) < 1) {
			throw new PPI_Exception('Invalid Log ID: ' . $iLogID);
		}
		$oLog = new PPI_Model_Shared('ppi_email_log', 'id');
		$this->adminLoad('admin/emaillog_view.tpl', array(
		    'log'      => $oLog->find($iLogID),
		    'leftMenu'  => true,
		    'pageTitle' => 'View User'
		));
	}

	/**
	 * Email log list
	 *
	 */
	protected function elList() {

		$oLog = new PPI_Model_Log();
		$logs = $oLog->getEmailLogs();

		$this->adminLoad('admin/emaillog_list', array(
			'logs'   => $logs,
		    'leftMenu'  => true,
		    'pageTitle' => 'Configuration'
		));

	}

    /**
     * AdminController::userView()
     * View a specific user
     * @return void
     */
    protected function userView() {
        if(($iUserID = $this->get('view')) != '') {
            $oUser = new APP_Model_User();
            $user = $oUser->find($iUserID);
            $user['role_name'] = PPI_Helper_User::getRoleNameFromID($user['role_id']);
            $this->adminLoad('admin/user_view', array(
                'user'      => $user,
                'leftMenu'  => true,
                'pageTitle' => 'View User'
            ));
        }
    }

	/**
	 * AdminController::userList()
	 * List all the users
	 * @return void
	 */
	protected function userList() {

		$oUser = new APP_Model_User();
		$sFilteredBy = null;
		$aClauses = array();

		if( ($iRoleFilter = $this->get('rolefilter', '')) !== '') {
			$sFilteredBy = PPI_Helper_User::getRoleNameNice(PPI_Helper_User::getRoleNameFromID($iRoleFilter));
			$aClauses[] = 'role_id = ' . $oUser->quote($iRoleFilter);
		}

		// Get the users
		$users = $oUser->getList(!empty($aClauses) ? implode(' AND ', $aClauses) : '')->fetchAll();

		// If there was a filter applied but returned no results, we default the userlist back to normal
		foreach($users as $key => $user) {
			$users[$key]['role_name'] = PPI_Helper_User::getRoleNameFromID($user['role_id']);
		}

		$sUsernameField = $this->getConfig()->system->usernameField;
		$this->adminLoad('admin/user_list', compact('users', 'sFilteredBy', 'sUsernameField'));
	}


    /**
     * AdminController::userAddEdit()
     * Add or Edit a user
     * @return void
     */
    protected function userAddEdit($p_sMode = 'create') {

    	$bEdit = ($p_sMode == 'edit');
   		$oUser = new APP_Model_User();
        $oForm = new PPI_Model_Form();
		$oForm->init('admin_user_addedit');
		//$oForm->setTinyMCE(true);

		$oForm->setFormStructure($oUser->getAdminAddEditFormStructure($p_sMode));
		if($oForm->isSubmitted()) {

            $aSubmitValues = $oForm->getSubmitValues();

            // Edit mode to set the primary key so that it performs an update
            if($bEdit && ($iUserID = $this->get($p_sMode)) > 0) {
                $aSubmitValues[$oUser->getPrimaryKey()] = $iUserID;
            }

            // Security check
            if($bEdit && $this->getAuthData(false)->role_id < $aSubmitValues[$oUser->getPrimaryKey()]) {
            	throw new PPI_Exception('Permission error: You cannot modify user privileges higher than your own.');
            }

            // Unique field check
            $sUsernameField = $this->getConfig()->system->usernameField;
            $aUniqueFields = array('email');
            if($sUsernameField != 'email') {
            	$aUniqueFields[] = $sUsernameField;
            }
            foreach($aUniqueFields as $sUniqueField) {
            	$aClause = array($sUniqueField . ' = ' . $oUser->quote($aSubmitValues[$sUsernameField]));
            	// If we're editing a user, make sure we're not checking against that same user (eg: we don't change the value)
            	if($bEdit) {
        			$aClause[] = $oUser->getPrimaryKey() . ' != ' . $oUser->quote($iUserID);
            	}
            	$aRecord = $oUser->getList(implode(' AND ', $aClause))->fetch();
            	if(!empty($aRecord)) {
            		$oForm->setElementError($sUniqueField, 'Another user has this field, it must be unique');
            	}
            }

            // Main validation check

            if($oForm->isValidated()) {

	            // Put the record (insert/update)
				$oUser->putRecord($aSubmitValues);

				/*
				$aAuthData = $this->getAuthData();
				foreach($aSubmitValues as $submitField => $submitValue) {
					$aAuthData[$submitField] = $submitValue;
				}
				$aAuthData['role_name'] = PPI_Helper_User::getRoleNameFromID($aAuthData['role_id']);
				$aAuthData['role_name_nice'] = PPI_Helper_User::getRoleNameNice($aAuthData['role_name']);
				$this->getSession()->setAuthData($aAuthData);
				*/
				$this->setFlashMessage('User account successfully ' . ($bEdit ? 'updated' : 'created') . '.');
	            $this->redirect('admin/user');

            }

		}

	    if($bEdit === true) {
            if( ($iUserID = $this->get('edit', 0)) < 1) {
                throw new PPI_Exception('Invalid User ID: ' . $iUserID);
            }
            // Set the defaults here
            $oForm->setDefaults($oUser->find($iUserID));
        }
        $aViewVars 	= array(
            'bEdit'       => $bEdit,
		    'formBuilder' => $oForm->getRenderInformation()  // FB Infos
        );
		$this->adminLoad('admin/user_addedit', $aViewVars);

    }

    function userUpdatePassword() {

    	$sUsername = $this->get('updatepassword', '');
    	if($sUsername == '') {
    		throw new PPI_Exception('Invalid Username');
    	}

    	$oUser = new APP_Model_User();

    	$aUser = $oUser->getRecord('username = ' . $oUser->quote($sUsername));
    	if(empty($aUser)) {
    		throw new PPI_Exception('Unable to find user information against: ' . $sUsername);
    	}
    	$iUserID = $aUser[$oUser->getPrimaryKey()];

    	$oForm = new PPI_Model_Form();
    	$oForm->init('admin_user_updatepassword', '', 'post');
    	$oForm->setFormStructure($oUser->getAdminUpdatePasswordFormStructure());

    	if($oForm->isSubmitted()) {

			$aFormValues = $oForm->getSubmitValues();
			if($aFormValues['password'] !== $aFormValues['password_confirm']) {
				$oForm->setElementError('password_confirm', 'Both passwords must match');
			}

    		if($oForm->isValidated()) {
    			$oUser->updatePassword($iUserID, $aFormValues['password']);
    			$this->redirect('admin/user');
    		}

    	}

    	$this->adminLoad('admin/user_updatepassword', array(
    		'formBuilder' => $oForm->getRenderInformation()
    	));
    }

    /**
     * AdminController::userDelete()
     * Delete a user
     * @return void
     */
    protected function userDelete() {


        if( ($iUserID = $this->get('delete', 0)) < 1) {
            throw new PPI_Exception('Invalid User ID: ' . $iUserID);
        }

        if($this->getAuthData(false)->id == $iUserID) {
        	$this->setFlashMessage('Unable to delete yourself', false);
        	$this->redirect('admin/user');
        }

        $oUser = new APP_Model_User();
        $oUser->delete($iUserID);
        $this->setFlashMessage('User successfully deleted.');
        $this->redirect('admin/user');
    }

    /**
     * Ticket CRUD Handler
     *
     */
	function ticket() {
		$sMode = $this->get('ticket');
		switch($sMode) {
			case 'create':
			case 'edit':
				$this->ticketAddEdit($sMode);
				break;

			case 'delete':
				$this->ticketDelete();
				break;

			case 'view':
				$this->ticketView();
				break;

			case 'list':
			default:
				$this->ticketList();
				break;
		}
	}

	/**
	 * Ticket Delete
	 *
	 */
	protected function ticketDelete() {
		if( ($iTicketID = $this->get('delete', '')) < 1) {
			throw new PPI_Exception('Invalid Ticket ID: ' . $iTicketID);
		}
		$aTicketIDs = explode(',', $iTicketID);
		$oTicket = new APP_Model_Ticket();
		foreach($aTicketIDs as $ticketID) {
			$oTicket->delete($ticketID);
		}
		$this->setFlashMessage('Ticket successfully deleted.');
		$this->redirect('admin/ticket');
	}

	/**
	 * AdminController::userList()
	 * List all the users
	 * @return void
	 */
	protected function ticketList() {
		$oTicket = new APP_Model_Ticket();

		$aParams = array();
		if( ($sSearchKeyword = $this->post('keyword', '')) != '') {
			$aParams['keyword'] = $sSearchKeyword;
		}


		$tickets = $oTicket->getTickets($aParams);

		$this->adminLoad('admin/ticket_list', array(
		    'tickets'    => $tickets,
		    'leftMenu'  => true,
		    'pageTitle' => 'Tickets'
		));
	}

	/**
	 * AdminController::userView()
	 * View a specific user
	 * @return void
	 */
	protected function ticketView() {
		$oTicket = new APP_Model_Ticket();
		if(($iTicketID = $this->get('view')) != '') {
			$ticket = $oTicket->select()
						->columns('t.*, u.first_name user_fn, u.last_name user_ln, uu.first_name user_assigned_fn, uu.last_name user_assigned_ln')
						->from($oTicket->getTableName() . ' t')
						->leftJoin('users u', 't.user_id=u.id')
						->leftJoin('users uu', 't.assigned_user_id=uu.id')
						->where('t.id = ' . $oTicket->quote($iTicketID))
						->order('created desc')
						->getList();

			if($ticket->countRows() == 0) {
				throw new PPI_Exception('Ticket does not exist.');
			}
			$this->adminLoad('admin/ticket_view', array(
			    'ticket'    => $ticket->fetch(),
			    'leftMenu'  => true,
			    'pageTitle' => 'View Ticket'
			));
		}
	}

	/**
	 *
	 * @param string $p_sMode The Mode (Create or Edit)
	 * @return void
	 */
	protected function ticketAddEdit($p_sMode = 'create') {

		$bEdit = ($p_sMode == 'edit');
		$oTicket = new APP_Model_Ticket();
		$oForm = new PPI_Model_Form();
		$oForm->init('admin_ticket_addedit');
		$oForm->setFormStructure($oTicket->getAdminAddEditFormStructure($p_sMode));
		if($oForm->isSubmitted() && $oForm->isValidated()) {
			$aSubmitValues = $oForm->getSubmitValues();
			if(!$bEdit) {
				$aSubmitValues['user_id'] = $this->getAuthData(false)->id;
				$aSubmitValues['created'] = time();
			}
			// Edit mode to set the primary key so that it performs an update
			if($bEdit && ($iTicketID = $this->get($p_sMode)) > 0) {
				$aSubmitValues[$oTicket->getPrimaryKey()] = $iTicketID;
			}
			// Put the record (insert/update)
			$oTicket->putRecord($aSubmitValues);
			$this->setFlashMessage('Ticket successfully ' . ($bEdit ? 'updated' : 'created') . '.');
			$this->redirect('admin/ticket');
		}

		if($bEdit === true) {
			if( ($iTicketID = $this->get('edit', 0)) < 1) {
				throw new PPI_Exception('Invalid User ID: ' . $iTicketID);
			}
			// Set the defaults here
			$oForm->setDefaults($oTicket->find($iTicketID));
		}
		$aViewVars 	= array(
			'bEdit'       => $bEdit,
			'formBuilder' => $oForm->getRenderInformation(),  // FB Infos
			'leftMenu'    => true
		);
		$this->adminLoad('admin/ticket_addedit', $aViewVars);

	}
}
