<?php

return [

    // ===================================
    // 1. General & Layout
    // ===================================
    'app_name' => 'KHDS',
    'logo' => 'Company Logo',

    'dashboard_title' => 'Dashboard',
    'welcome_title' => 'Welcome',
    'logout_btn' => 'Log Out',
    'users_link' => 'Users Management',
    'settings_link' => 'Settings',

    // ===================================
    // 2. Roles Management
    // ===================================
    'roles_management' => 'Roles Management',
    'role_name' => 'Role Name',
    'role_permissions' => 'Role Permissions',
    'permissions_title' => 'Available Permissions',
    'create_new_role' => 'Create New Role',
    'enter_role_name_placeholder' => 'Enter role name',
    'no_permissions_assigned' => 'No permissions assigned to this role.',
    'select_all_btn' => 'Select All',
    'unselect_all_btn' => 'Deselect All',
    'save_role_btn' => 'Save Role',
    'edit_role' => 'Edit Role',
    'update_role_btn' => 'Update Role',

    // ===================================
    // 3. Users Management
    // ===================================
    'users_management' => 'Users Management',
    'add_new_user' => 'Add New User',
    'edit_user' => 'Edit User',
    'user_name' => 'Name',
    'user_email' => 'Email',
    'user_role' => 'Role',

    'password' => 'Password',
    'confirm_password' => 'Confirm Password',
    'optional' => 'Optional',
    'leave_blank_for_no_change' => 'Leave blank for no change',
    'select_role_placeholder' => 'Select Role',

    'save_user_btn' => 'Save User',
    'update_user_btn' => 'Update User',
    'edit_btn' => 'Edit',
    'delete_btn' => 'Delete',

    'user_created_success' => 'User created successfully.',
    'user_updated_success' => 'User updated successfully.',
    'user_deleted_success' => 'User deleted successfully.',
    'cannot_delete_self' => 'You cannot delete your own account.',
    'enter_name_placeholder' => 'Enter your name',

    // ===================================
    // 4. Permissions
    // ===================================
    'permission_view users' => 'View Users',
    'permission_add users' => 'Add Users',
    'permission_edit users' => 'Edit Users',
    'permission_delete users' => 'Delete Users',
    'permission_view tickets' => 'View Tickets',
    'permission_add tickets' => 'Add Tickets',
    'permission_edit tickets' => 'Edit Tickets',
    'permission_delete tickets' => 'Delete Tickets',

    // ===================================
    // 5. Login Page
    // ===================================
    'please_login_message' => 'Please enter your login credentials.',
    'email_label' => 'Email:',
    'enter_email_placeholder' => 'Enter your email address',
    'password_label' => 'Password:',
    'enter_password_placeholder' => 'Enter your password',
    'remember_me' => 'Remember me',
    'login_btn' => 'Log In',
    'login_failed' => 'The provided credentials do not match our records.',

    // ===================================
    // 6. Register Page
    // ===================================
    'register_title' => 'Create New Account',
    'full_name_label' => 'Full Name:',
    'already_registered' => 'Already have an account?',
    'register_btn' => 'Register',
    'login_link' => 'Log In',

    // ===================================
    // 7. Teams Management
    // ===================================
    'teams_management' => 'Teams Management',
    'add_new' => '+ Add New Team',
    'table_name' => 'Team Name',
    'table_specialization' => 'Specialization',
    'table_members_count' => 'Members Count',
    'table_actions' => 'Actions',
    'edit_members' => 'Edit Members',
    'not_available' => 'N/A',
    'no_teams_yet' => 'No teams registered yet.',

    'create_title' => 'Create New Team',
    'team_name' => 'Team Name',
    'team_specialization' => 'Specialization (e.g., Web Development)',
    'team_leader' => 'Team Leader',
    'select_leader' => 'Select Team Leader',
    'no_agents_found' => 'No Agents Available',

    'save_create' => 'Save and Create Team',
    'back_to_list' => 'Back to Teams List',
    'edit_members_title' => 'Edit Team Members:',
    'edit_members_subtitle' => 'Select the users who belong to this team.',
    'save_members' => 'Save Team Members',
    'cancel' => 'Cancel',

    'created_success' => 'Team created successfully.',
    'members_updated_success' => 'Team members updated successfully.',
    'success_heading' => 'Success!',

    // ===================================
    // 8. Projects Management
    // ===================================
    'projects_management' => 'Projects Management',
    'create_project' => 'Create New Project',
    'edit_project' => 'Edit Project',
    'project_name' => 'Project Name',
    'project_description' => 'Project Description',
    'project_client' => 'Client',
    'project_team' => 'Responsible Team',
    'project_status' => 'Status',
    'project_created_success' => 'Project created successfully.',
    'project_updated_success' => 'Project updated successfully.',
    'project_deleted_success' => 'Project deleted successfully.',
    'no_projects_yet' => 'No projects yet.',
    'status_active' => 'Active',
    'status_pending' => 'Pending',
    'status_completed' => 'Completed',
    'save_btn' => 'Save',
    'update_btn' => 'Update',
    'delete_btn' => 'Delete',
    'edit_btn' => 'Edit',
    'confirm_delete' => 'Are you sure you want to delete this?',
    'enter_project_name' => 'Enter project name',
    'enter_project_description' => 'Enter project description',
    'form_has_errors' => 'There are some errors in your submission, please check the form.',

    // ===================================
    // 9. Tickets Management (NEW)
    // ===================================
    'tickets_management' => 'Tickets Management',
    'create_ticket' => 'Create New Ticket',
    'edit_ticket' => 'Edit Ticket',

    'ticket_title' => 'Ticket Title',
    'ticket_description' => 'Ticket Description',
    'ticket_type' => 'Ticket Type',
    'ticket_priority' => 'Ticket Priority',
    'ticket_status' => 'Ticket Status',
    'ticket_project' => 'Related Project',
    'assigned_to' => 'Assigned To',
    'team_id' => 'Responsible Team',
    'created_by' => 'Created By',
    'none' => 'None',

    'type_task' => 'Task',
    'type_bug' => 'Bug',
    'type_feature' => 'Feature',
    'type_improvement' => 'Improvement',

    'priority_low' => 'Low',
    'priority_medium' => 'Medium',
    'priority_high' => 'High',
    'priority_urgent' => 'Urgent',

    'status_open' => 'Open',
    'status_in_progress' => 'In Progress',
    'status_resolved' => 'Resolved',
    'status_closed' => 'Closed',

    'save_ticket_btn' => 'Save Ticket',
    'update_ticket_btn' => 'Update Ticket',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'confirm_delete_ticket' => 'Are you sure you want to delete this ticket?',
    'no_tickets_found' => 'No tickets found.',

    'ticket_created_success' => 'Ticket created successfully.',
    'ticket_updated_success' => 'Ticket updated successfully.',
    'ticket_deleted_success' => 'Ticket deleted successfully.',
    'ticket_assigned_to' => 'Ticket assigned to',
    'ticket_id' => 'Ticket ID',
    'ticket_team' => 'Responsible Team',
    'ticket_created_by' => 'Created by',
    'actions' => 'Actions',
    'contracts_management' => 'Contracts Management',
    'create_contract' => 'Create New Contract',
    'edit_contract' => 'Edit Contract',
    'contract_number' => 'Contract Number',
    'contract_client' => 'Client',
    'contract_start_date' => 'Start Date',
    'contract_end_date' => 'End Date',
    'contract_status' => 'Status',
    'contract_attachment' => 'Attachment',
    'status_active' => 'Active',
    'status_pending' => 'Pending',
    'status_expired' => 'Expired',
    'view_attachment' => 'View Attachment',
    'current_file' => 'Current File',
    'contract_created_success' => 'Contract created successfully.',
    'contract_updated_success' => 'Contract updated successfully.',
    'contract_deleted_success' => 'Contract deleted successfully.',
    'no_contracts_found' => 'No contracts found.',
    'notes' => 'Notes',
    'select_client' => 'Select Client',
    'select_project' => 'Select Project',
    'contract_creator' => 'Created By',
    'contract_project' => 'Related Project',
    'status_cancelled' => 'Cancelled',

    // ✅ Invoices
    'invoices_management' => 'Invoices Management',
    'create_invoice' => 'Create New Invoice',
    'edit_invoice' => 'Edit Invoice',
    'no_invoices_found' => 'No invoices found',

    // Fields
    'invoice_number' => 'Invoice Number',
    'invoice_date' => 'Invoice Date',
    'invoice_client' => 'Client',
    'invoice_project' => 'Project',
    'invoice_total' => 'Total',
    'invoice_status' => 'Status',
    'invoice_attachment' => 'Attachment',
    'invoice_creator' => 'Created By',

    // Status
    'status_paid' => 'Paid',
    'status_pending' => 'Pending',
    'status_unpaid' => 'Unpaid',

    // Buttons
    'actions' => 'Actions',
    'view_attachment' => 'View Attachment',
    'edit_btn' => 'Edit',
    'delete_btn' => 'Delete',
    'save_btn' => 'Save',
    'update_btn' => 'Update',
    'confirm_delete' => 'Are you sure you want to delete this invoice?',
    'none' => 'None',
    'current_file' => 'Current File',
    'invoice_updated_success' => 'Invoice updated successfully.',
    'management_title' => 'Teams Management',
    'manage_permissions' => 'Manage Permissions',
    'manage_permissions_for' => 'Manage permissions for:',
    'save_permissions' => 'Save Permissions',
    'permissions_updated_successfully' => 'Permissions updated successfully',
    'permission_view projects' => 'View Projects',
    'permission_add projects' => 'Add Projects',
    'permission_edit projects' => 'Edit Projects',
    'permission_delete projects' => 'Delete Projects',

    'permission_view contracts' => 'View Contracts',
    'permission_add contracts' => 'Add Contracts',
    'permission_edit contracts' => 'Edit Contracts',
    'permission_delete contracts' => 'Delete Contracts',

    'permission_view invoices' => 'View Invoices',
    'permission_add invoices' => 'Add Invoices',
    'permission_edit invoices' => 'Edit Invoices',
    'permission_delete invoices' => 'Delete Invoices',

    'permission_view teams' => 'View Teams',
    'permission_add teams' => 'Add Teams',
    'permission_edit teams' => 'Edit Teams',
    'permission_delete teams' => 'Delete Teams',

    'permission_manage permissions' => 'Manage Permissions',

    'permission_view Roles' => 'View Roles',
    'permission_add Roles' => 'Add Roles',
    'permission_edit Roles' => 'Edit Roles',
    'permission_delete Roles' => 'Delete Roles',

    'permission_view own tickets' => 'View Own Tickets',
    'permission_view own projects' => 'View Own Projects',
    'permission_view own contracts' => 'View Own Contracts',
    'permission_view own invoices' => 'View Own Invoices',

    'permission_assignees tickets' => 'Assigned Tickets',

    'user_details' => 'User Details',
    'back_to_list' => 'Back to Users List',
    'user_name' => 'User Name',
    'user_email' => 'User Email',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
    'user_roles' => 'User Roles',
    'user_permissions' => 'User Permissions',
    'no_roles_assigned' => 'No roles assigned to this user.',
    'no_permissions_assigned' => 'No permissions assigned to this user.',
    'view' => 'View',
    'team_details' => 'Team Details',
    'back_to_teams' => 'Back to Teams',
    'team_name' => 'Team Name',
    'team_specialization' => 'Specialization',
    'team_lead' => 'Team Lead',
    'members_count' => 'Members Count',
    'team_members' => 'Team Members',
    'no_members_in_team' => 'No members in this team yet',
    'team_projects' => 'Team Projects',
    'no_projects_assigned' => 'No projects assigned',
    'view_user' => 'View User',
    'not_available' => 'Not Available',
    'not_assigned' => 'Not Assigned',
    'project_no_name' => 'Unnamed Project',
    'permission_denied' => 'Permission Denied',
    'select_agent' => 'Select Agent',
    'agent_assigned_success' => 'You have been successfully assigned to this project.',
    'project_details' => 'Project Details',
    'back_to_projects' => 'Back to Projects',
    'Assigned_To' => 'Assigned To',
    'ticket_details' => 'Ticket Details',
    'back_to_tickets' => 'Back to Tickets',
    'invoice_created_success' => 'Invoice created successfully.',
    'permission_assign projects' => 'Assign Projects',
    'contract_notes' => 'Contract Notes',
    'contract_details' => 'Contract Details',
    'back' => 'Back',
       'invoice_details' => 'Invoice Details',
    'invoices_management' => 'Invoices Management',
    'invoice_information' => 'Invoice Information',
    'client_project' => 'Client & Project',
    'back' => 'Back',
    'edit_btn' => 'Edit',
    'delete_btn' => 'Delete',
    'confirm_delete' => 'Are you sure you want to delete this invoice?',

    // Invoice Data
    'invoice_number' => 'Invoice Number',
    'invoice_date' => 'Invoice Date',
    'invoice_client' => 'Client',
    'invoice_project' => 'Project',
    'invoice_creator' => 'Created By',
    'invoice_total' => 'Total',
    'invoice_status' => 'Status',
    'invoice_attachment' => 'Attachment',
    'view_attachment' => 'View Attachment',
    'no_attachment' => 'No Attachment',

    // Statuses
    'status_paid' => 'Paid',
    'status_pending' => 'Pending',
    'status_unpaid' => 'Unpaid',

    // Flash Messages
    'invoice_created_success' => 'Invoice created successfully.',
    'invoice_updated_success' => 'Invoice updated successfully.',
    'invoice_deleted_success' => 'Invoice deleted successfully.',
    'permission_denied' => 'You do not have permission to access this page.',

    // Other
    'none' => 'None',

    'no_tickets_yet' => 'No tickets yet.',
];
