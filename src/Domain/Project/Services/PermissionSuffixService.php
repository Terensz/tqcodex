<?php

namespace Domain\Project\Services;

class PermissionSuffixService
{
    /**
     * These constants are only suffixes of permissions. The whole will get a prefix, like "View", "Edit", "Delete".
     * Suffix firts part is the route prefix (Admin, User)
     */

    /**
     * User types
     */
    public const SUFFIX_USER_SUPER_ADMIN = 'UserSuperAdmin';

    public const SUFFIX_USER_KEY_ACCOUNT_MANAGER = 'UserKeyAccountManager';

    public const SUFFIX_USER_FINANCIAL_ACCOUNTING_MANAGER = 'UserFinancialAccountingManager';

    public const SUFFIX_USER_CUSTOMER_INTERFACE = 'UserRegisteredCustomer';

    public const SUFFIX_USER_VISITOR = 'UserVisitor';

    /**
     * Roles
     */
    public const SUFFIX_ROLE_SUPER_ADMIN = 'RoleSuperAdmin';

    public const SUFFIX_ROLE_KEY_ACCOUNT_MANAGER = 'RoleKeyAccountManager';

    public const SUFFIX_ROLE_FINANCIAL_ACCOUNTING_MANAGER = 'RoleFinancialAccountingManager';

    // public const SUFFIX_ROLE_CUSTOMER = 'RoleCustomer';

    public const SUFFIX_ROLE_REGISTERED_CUSTOMER = 'RoleRegisteredCustomer';

    public const SUFFIX_ROLE_VISITOR = 'RoleVisitor';

    /**
     * Admin pages
     */
    public const SUFFIX_ADMIN_CUSTOMERS_CASHFLOW_DATA = 'AdminCustomersCashflowData'; // Penzforgalmi adatok

    public const SUFFIX_ADMIN_ACCOUNT_SETTLEMENT_LOG = 'AdminAccountSettlementLog'; // Szamlakiegyenlitesi naplo

    public const SUFFIX_ADMIN_CUSTOMER_REFERRAL_SYSTEM = 'AdminCustomerReferralSystem';

    public const SUFFIX_ADMIN_SERVICE_PRICES = 'AdminServicePrices';

    public const SUFFIX_ADMIN_DASHBOARD = 'AdminDashboard';

    public const SUFFIX_ADMIN_PROFILE = 'AdminProfile';

    public const SUFFIX_ADMIN_ADMINS = 'AdminAdmins';

    public const SUFFIX_ADMIN_ROLES = 'AdminRoles';

    public const SUFFIX_ADMIN_PERMISSIONS = 'AdminPermissions';

    public const SUFFIX_ADMIN_ADMINS_ACTIVITY_LOG = 'AdminAdminsActivityLog';

    public const SUFFIX_ADMIN_SYSTEM_ERROR_LOG = 'AdminSystemErrorLog';

    public const SUFFIX_ADMIN_VISIT_LOG = 'AdminVisitLog';

    public const SUFFIX_ADMIN_SYSTEM_SETTINGS = 'AdminSystemSettings';

    public const SUFFIX_ADMIN_CUSTOMERS = 'AdminCustomers';

    public const SUFFIX_ADMIN_CUSTOMERS_ACTIVITY_LOG = 'AdminCustomersActivityLog';

    /**
     * Webshop routes
     */
    public const SUFFIX_ADMIN_WEBSHOP_DASHBOARD = 'AdminWebshopDashboard';

    /**
     * Customer pages
     */

    /**
     * Public area pages
     */
    public const SUFFIX_PUBLIC_AREA = 'PublicArea';
}
