<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\LoanProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SavingsAccountController;
use App\Http\Controllers\SavingsProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

//-------------login and user------------------------

// Display the login form for the selected organization
Route::get('/', [UserController::class, 'showLoginForm'])->name('login');

// Handle user login
Route::post('/', [UserController::class, 'login'])->name('loggedin');

// User dashboard
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard')->middleware('auth');

// Logout route
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Edit Profile Page
Route::get('/user/edit-profile', [UserController::class, 'editProfile'])->name('user.edit-profile')->middleware('auth');

// Update Password
Route::post('/user/update-password', [UserController::class, 'updatePassword'])->name('user.update-password')->middleware('auth');

//-------------------org-setting--------
//-----------------org-details-----------
Route::get('/settings/organization-details', [OrganizationController::class, 'index'])->name('organization.details')->middleware('auth');
Route::put('/settings/organization-details/{id}', [OrganizationController::class, 'update'])->name('organization.update')->middleware('auth');

//---------------org-branches--------------
// Display all branches
Route::get('/settings/branches',  [OrganizationController::class, 'branches'])->name('settings.branches.index')->middleware('auth');

// Display the branch creation form
Route::get('/settings/branches/create', [OrganizationController::class, 'createBranch'])->name('settings.branches.create')->middleware('auth');

// Store a new branch
Route::post('/settings/branches', [OrganizationController::class, 'storeBranch'])->name('settings.branches.store')->middleware('auth');

// Display the branch update form
Route::get('/settings/branches/{branch}/edit', [OrganizationController::class, 'editBranch'])->name('settings.branches.edit')->middleware('auth');

// Update a branch
Route::put('/settings/branches/{branch}', [OrganizationController::class, 'updateBranch'])->name('settings.branches.update')->middleware('auth');

//-----org users----
Route::get('/settings/users', [OrganizationController::class, 'users'])->name('settings.users.index')->middleware('auth');
// Display disabled users
Route::get('/settings/users/disabled', [OrganizationController::class, 'disabledUsers'])->name('settings.users.disabled')->middleware('auth');
// Display the user creation form
Route::get('/settings/users/create', [OrganizationController::class, 'createUser'])->name('settings.users.create')->middleware('auth');
// Store a new user
Route::post('/settings/users', [OrganizationController::class, 'storeUser'])->name('settings.users.store')->middleware('auth');
// Display the user update form
Route::get('/settings/users/{nowuser}/edit', [OrganizationController::class, 'editUser'])->name('settings.users.edit')->middleware('auth');
// Update a user
Route::put('/settings/users/update/{nowuser}', [OrganizationController::class, 'updateUser'])->name('settings.users.update')->middleware('auth');

//disable user
Route::put('/settings/users/disable/{nowuser}', [OrganizationController::class, 'disableUser'])->name('settings.users.disable')->middleware('auth');
//enable user
Route::put('/settings/users/{nowuser}', [OrganizationController::class, 'enableUser'])->name('settings.users.enable')->middleware('auth');

//accounts
Route::middleware(['auth'])->group(function () {
    Route::get('/settings/accounts', [AccountController::class, 'index'])->name('settings.accounts.index');
    Route::get('/settings/accounts/equity', [AccountController::class, 'equity'])->name('settings.accounts.equity');
    Route::get('/settings/accounts/expense', [AccountController::class, 'expense'])->name('settings.accounts.expense');
    Route::get('/settings/accounts/income', [AccountController::class, 'income'])->name('settings.accounts.income');
    Route::get('/settings/accounts/liability', [AccountController::class, 'liability'])->name('settings.accounts.liability');
    Route::get('/settings/accounts/create', [AccountController::class, 'create'])->name('settings.accounts.create');
    Route::post('/settings/accounts', [AccountController::class, 'store'])->name('settings.accounts.store');
    Route::get('/settings/accounts/{account}/edit', [AccountController::class, 'edit'])->name('settings.accounts.edit');
    Route::put('/settings/accounts/{account}', [AccountController::class, 'update'])->name('settings.accounts.update');
});
//savings product
Route::middleware(['auth'])->group(function () {
    Route::get('/settings/savings-products', [SavingsProductController::class, 'index'])->name('settings.savings-products.index');
    Route::get('/settings/savings-products/create', [SavingsProductController::class, 'create'])->name('settings.savings-products.create');
    Route::post('/settings/savings-products', [SavingsProductController::class, 'store'])->name('settings.savings-products.store');
    Route::get('/settings/savings-products/{savings_product}/edit', [SavingsProductController::class, 'edit'])->name('settings.savings-products.edit');
    Route::put('/settings/savings-products/{savings_product}', [SavingsProductController::class, 'update'])->name('settings.savings-products.update');
});
//loan product
Route::middleware(['auth'])->group(function () {
    Route::get('/settings/loan-products', [LoanProductController::class, 'index'])->name('settings.loan-products.index');
    Route::get('/settings/loan-products/create', [LoanProductController::class, 'create'])->name('settings.loan-products.create');
    Route::post('/settings/loan-products', [LoanProductController::class, 'store'])->name('settings.loan-products.store');
    Route::get('/settings/loan-products/{loan_product}/edit', [LoanProductController::class, 'edit'])->name('settings.loan-products.edit');
    Route::put('/settings/loan-products/{loan_product}', [LoanProductController::class, 'update'])->name('settings.loan-products.update');
});

//data imports
Route::middleware(['auth'])->group(function () {
    Route::get('/settings/data-imports', [ImportController::class, 'index'])->name('settings.data-imports.index');
    Route::get('/settings/data-imports/clients/create', [ImportController::class, 'client_create'])->name('settings.data-imports.clients.create'); 
    Route::get('/settings/data-imports/clients/template', [ImportController::class, 'clients_template'])->name('settings.data-imports.clients.template');    
    Route::post('/settings/data-imports/clients/store', [ImportController::class, 'client_store'])->name('settings.data-imports.clients.store'); 
    Route::get('/settings/data-imports/savings-accounts/create', [ImportController::class, 'savings_accounts_create'])->name('settings.data-imports.savings-accounts.create'); 
    Route::get('/settings/data-imports/savings-accounts/template', [ImportController::class, 'savings_accounts_template'])->name('settings.data-imports.savings-accounts.template');    
    Route::post('/settings/data-imports/savings-accounts/store', [ImportController::class, 'savings_accounts_store'])->name('settings.data-imports.savings-accounts.store');
    Route::get('/settings/data-imports/loans/create', [ImportController::class, 'loans_create'])->name('settings.data-imports.loans.create'); 
    Route::get('/settings/data-imports/loans/template', [ImportController::class, 'loans_template'])->name('settings.data-imports.loans.template');    
    Route::post('/settings/data-imports/loans/store', [ImportController::class, 'loans_store'])->name('settings.data-imports.loans.store'); 
});

//clients
Route::middleware(['auth'])->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create-client', [ClientController::class, 'create'])->name('clients.create-client');
    Route::get('/clients/{client}', [ClientController::class, 'client'])->name('clients.client');    
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::get('/clients/{client}/detail', [ClientController::class, 'detail'])->name('clients.detail');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
});
//Savings account to continue give transaction
Route::middleware(['auth'])->group(function () {
    Route::get('clients/{client}/savings-accounts/{account}', [SavingsAccountController::class, 'index'])->name('savings-accounts.index');
    Route::get('clients/{client}/savings-accounts/{account}/ledger', [SavingsAccountController::class, 'ledger'])->name('savings-accounts.ledger');
    Route::get('savings-accounts/create/{client}', [SavingsAccountController::class, 'create'])->name('savings-accounts.create');
    //Route::get('clients/{client}/savings-accounts/create_account', [SavingsAccountController::class, 'create_account'])->name('savings-accounts.create_account');
    Route::post('savings-accounts/', [SavingsAccountController::class, 'store'])->name('savings-accounts.store');
    Route::get('clients/{client}/savings-accounts/deposit/{account}', [SavingsAccountController::class, 'deposit'])->name('savings-accounts.deposit');
    Route::post('savings-accounts/deposit', [SavingsAccountController::class, 'store_deposit'])->name('savings-accounts.store-deposit');
    Route::post('savings-accounts/deposit-reverse', [SavingsAccountController::class, 'reverse_deposit'])->name('savings-accounts.reverse-deposit');
    Route::get('clients/{client}/savings-accounts/withdraw/{account}', [SavingsAccountController::class, 'withdraw'])->name('savings-accounts.withdraw');
    Route::post('savings-accounts/withdraw', [SavingsAccountController::class, 'store_withdraw'])->name('savings-accounts.store-withdraw');
    Route::post('savings-accounts/withdrwal-reverse', [SavingsAccountController::class, 'reverse_withdraw'])->name('savings-accounts.reverse-withdraw');
    Route::delete('clients/{client}/savings-accounts/{savings_account}', [SavingsAccountController::class, 'destroy'])->name('savings-accounts.destroy');
    // Add more routes for deposits and withdrawals
});

//loan
Route::middleware(['auth'])->group(function () {
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/pending-approval', [LoanController::class, 'appraised'])->name('loans.pending-approval');
    Route::get('/loans/approved', [LoanController::class, 'approved'])->name('loans.approved');
    Route::get('/loans/disbursed', [LoanController::class, 'disbursed'])->name('loans.disbursed');
    Route::get('/loans/cleared', [LoanController::class, 'cleared'])->name('loans.cleared');
    Route::get('/loans/waived', [LoanController::class, 'waived'])->name('loans.waived');
    Route::get('/loans/deferred', [LoanController::class, 'deferred'])->name('loans.deferred');
    Route::get('clients/{client}/loans/create', [LoanController::class, 'create'])->name('loans.create'); 
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}', [loanController::class, 'loan'])->name('loans.loan');
    Route::put('/loans/{loan}', [LoanController::class, 'update'])->name('loans.update');  
    Route::put('/loans/approve/{loan}', [LoanController::class, 'approve'])->name('loans.approve');
    Route::put('/loans/disburse/{loan}', [LoanController::class, 'disburse'])->name('loans.disburse');
    Route::post('/loans/disburse-reverse', [LoanController::class, 'disburse_reverse'])->name('loans.disburse-reverse');
    Route::put('/loans/defer/{loan}', [LoanController::class, 'defer'])->name('loans.defer');
    Route::delete('/loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy'); 
    Route::get('/loans/{loan}/schedule', [loanController::class, 'schedule'])->name('loans.schedule'); 
    Route::get('/loans/{loan}/ledger', [loanController::class, 'loan_ledger'])->name('loans.ledger'); 
    Route::get('/loans/{loan}/schedule-print', [loanController::class, 'schedule_print'])->name('loans.schedule-print'); 
    Route::get('/loans/{loan}/ledger', [loanController::class, 'ledger'])->name('loans.ledger'); 
    Route::get('/loans/{loan}/payment-create', [loanController::class, 'loan_payment_create'])->name('loans.payment-create'); 
    Route::put('/loans/payment-store/{loan}', [LoanController::class, 'loan_payment_store'])->name('loans.payment-store'); 
    Route::post('/loans/payment-reverse', [LoanController::class, 'loan_payment_reverse'])->name('loans.payment-reverse');
});


//Transactions
Route::middleware(['auth'])->group(function () {
    Route::get('/transactions/other-income', [TransactionController::class, 'other_income_index'])->name('transactions.other-income.index');
    Route::get('/transactions/other-income/create', [TransactionController::class, 'other_income_create'])->name('transactions.other-income.create');
    Route::get('/transactions/client-fees/{client}', [TransactionController::class, 'client_fees_create'])->name('transactions.client-fees');
    Route::post('/transactions/other-income', [TransactionController::class, 'other_income_store'])->name('transactions.other-income.store');
    Route::post('/transactions/client-fees', [TransactionController::class, 'client_fees_store'])->name('transactions.client-fees.store');
    Route::get('/transactions/expense', [TransactionController::class, 'expense_index'])->name('transactions.expense.index');
    Route::get('/transactions/expense/create', [TransactionController::class, 'expense_create'])->name('transactions.expense.create');
    Route::post('/transactions/expense', [TransactionController::class, 'expense_store'])->name('transactions.expense.store');
    Route::get('/transactions/non-cash', [TransactionController::class, 'non_cash_index'])->name('transactions.non-cash.index');
    Route::get('/transactions/non-cash/create', [TransactionController::class, 'non_cash_create'])->name('transactions.non-cash.create');
    Route::post('/transactions/non-cash', [TransactionController::class, 'non_cash_store'])->name('transactions.non-cash.store');
    Route::get('/transactions/cash-transfer', [TransactionController::class, 'cash_transfer_index'])->name('transactions.cash-transfer.index');
    Route::get('/transactions/cash-transfer/create', [TransactionController::class, 'cash_transfer_create'])->name('transactions.cash-transfer.create');
    Route::post('/transactions/cash-transfer', [TransactionController::class, 'cash_transfer_store'])->name('transactions.cash-transfer.store');
    Route::post('/transactions/expense-reverse', [TransactionController::class, 'expense_reverse'])->name('transactions.expense-reverse');
    Route::post('/transactions/other-income-reverse', [TransactionController::class, 'other_income_reverse'])->name('transactions.other-income-reverse');
    Route::post('/transactions/cash-transfer-reverse', [TransactionController::class, 'cash_transfer_reverse'])->name('transactions.cash-transfer-reverse');
    Route::post('/transactions/non-cash-reverse', [TransactionController::class, 'non_cash_reverse'])->name('transactions.reverse-non-cash');

});

//cash accounts
Route::middleware(['auth'])->group(function () {
    Route::get('/cash-accounts', [AccountController::class, 'cash_account_index'])->name('cash-accounts.index');
    Route::get('/cash-accounts/{account}', [AccountController::class, 'cash_account'])->name('cash-accounts.account');   
    Route::get('/cash-accounts-bank', [AccountController::class, 'bank_account'])->name('cash-accounts-bank');
    Route::get('/cash-accounts-vault', [AccountController::class, 'vault_account'])->name('cash-accounts-vault');
    Route::get('/cash-accounts-mobile-money', [AccountController::class, 'mobile_money_account'])->name('cash-accounts-mobile-money');
});

//reports
Route::middleware(['auth'])->group(function () {
    Route::get('/reports/cash-book', [ReportController::class, 'cash_book'])->name('reports.cashbook');
    Route::get('/reports/cash-book-filter', [ReportController::class, 'cash_book_filter'])->name('reports.cashbook-filter');
    Route::get('/reports/loan/disbursement', [ReportController::class, 'disbursement'])->name('reports.loans.disbursement');
    Route::get('/reports/loan/disbursement-filter', [ReportController::class, 'disbursement_filter'])->name('reports.loans.disbursement-filter');
    Route::get('/reports/loan/portfolio', [ReportController::class, 'portfolio'])->name('reports.loans.portfolio');
    Route::get('/reports/loan/portfolio-filter', [ReportController::class, 'portfolio_filter'])->name('reports.loans.portfolio-filter');
   
});

//----------------superadmin-org-----------------------------
// All Organizations
Route::get('/super-admin', [SuperAdminController::class, 'index'])->name('super-admin.index')->middleware('auth');

//add new org form
Route::get('/super-admin/organizations/create', [SuperAdminController::class, 'create'])->name('super-admin.organizations.create')->middleware('auth');

//save new org
Route::post('/super-admin/organizations', [SuperAdminController::class, 'store'])->name('super-admin.organizations.store')->middleware('auth');

//update org form
Route::get('/super-admin/organizations/{organization}/edit',  [SuperAdminController::class, 'edit'])->name('super-admin.organizations.edit')->middleware('auth');

//update org superadmin
Route::put('/super-admin/organizations/{organization}', [SuperAdminController::class, 'update'])->name('super-admin.organizations.update')->middleware('auth');

//---------------------------------------------branch-------------------------------------------
// Display all branches
Route::get('/super-admin/branches',  [SuperAdminController::class, 'branches'])->name('super-admin.branches.index')->middleware('auth');

// Display the branch creation form
Route::get('/super-admin/branches/create', [SuperAdminController::class, 'createBranch'])->name('super-admin.branches.create')->middleware('auth');

// Store a new branch
Route::post('/super-admin/branches', [SuperAdminController::class, 'storeBranch'])->name('super-admin.branches.store')->middleware('auth');

// Display the branch update form
Route::get('/super-admin/branches/{branch}/edit', [SuperAdminController::class, 'editBranch'])->name('super-admin.branches.edit')->middleware('auth');

// Update a branch
Route::put('/super-admin/branches/{branch}', [SuperAdminController::class, 'updateBranch'])->name('super-admin.branches.update')->middleware('auth');

//-----------------------------------------superadmin-users--------------------------------------------------
// Display all users
Route::get('/super-admin/users', [SuperAdminController::class, 'users'])->name('super-admin.users.index')->middleware('auth');

// Display the user creation form
Route::get('/super-admin/users/create', [SuperAdminController::class, 'createUser'])->name('super-admin.users.create')->middleware('auth');

// Store a new user
Route::post('/super-admin/users', [SuperAdminController::class, 'storeUser'])->name('super-admin.users.store')->middleware('auth');

// Display the user update form
Route::get('/super-admin/users/{user}/edit', [SuperAdminController::class, 'editUser'])->name('super-admin.users.edit')->middleware('auth');

// Update a user
Route::put('/super-admin/users/{user}', [SuperAdminController::class, 'updateUser'])->name('super-admin.users.update')->middleware('auth');

