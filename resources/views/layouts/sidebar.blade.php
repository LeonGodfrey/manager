  <!-- Main Sidebar Container -->
  <aside class="main-sidebar mi-side-color sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    {{-- <a href="{{route('dashboard')}}" class="brand-link bg-light">     
      <span class="brand-text text-success"><b>@yield('org_name')</b></span>
    </a> --}}

    <a href="{{route('dashboard')}}" class="brand-link  bg-light">
      <img src="{{ asset('storage/logos/microfin_logo_alone.svg') }}" alt="Logo" class="brand-image" style="opacity: .8">
      <span class="brand-text text-success"><b>@yield('org_name')</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{route('clients.index')}}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p class="text-light">
                Clients
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="cash_book.php" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Cash Book
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('loans.index')}}" class="nav-link">
              <i class="nav-icon fas fa-hand-holding-usd"></i>
              <p>
                Loans
              </p>
            </a>            
          </li>
          <li class="nav-item">
            <a href="{{route('cash-accounts.index')}}" class="nav-link">
              <i class="nav-icon far fa-money-bill-alt"></i>
              <p>
                Cash Accounts
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Transactions
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('transactions.cash-transfer.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Cash Transfers</p>
                </a>
              </li>              
              <li class="nav-item">
                <a href="{{route('transactions.expense.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Expenses</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('transactions.other-income.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Other Incomes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('transactions.non-cash.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Non Cash Transactions</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-file-contract"></i>
              <p>
                Reports
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Financials</p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Cash flow Statement</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Income Statement</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Balance Sheet</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Breakdown Per GLA</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="pages/charts/flot.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Loan Reports</p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Loan Portfolio</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Arrears Report</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Disbursment Report</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Loan Tracking Report</p>
                    </a>
                  </li>                 
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Portfolio At Risk</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="pages/charts/inline.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customer Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/uplot.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Savings Portfolio</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/uplot.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Expense Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/uplot.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>SMS Reports</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/uplot.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User Logs</p>
                </a>
              </li>              
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Settings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('organization.details')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Details</p>
                </a>
              </li>
              </li>
              <li class="nav-item">
                <a href="{{route('settings.branches.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Branches</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('settings.accounts.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Chart Of Accounts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('settings.users.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('settings.savings-products.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Savings Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('settings.loan-products.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Loan Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="org_data_imports.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Imports</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>