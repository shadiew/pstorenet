<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="<?php echo $cfg_baseurl; ?>" class="app-brand-link">
              <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                    fill="#7367F0" />
                  <path
                    opacity="0.06"
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                    fill="#161616" />
                  <path
                    opacity="0.06"
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                    fill="#161616" />
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                    fill="#7367F0" />
                </svg>
              </span>
              <span class="app-brand-text demo menu-text fw-bold"><?php echo $data_settings['web_name']; ?></span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
              <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboards -->

            <li class="menu-item <?php echo ($page == 'dashboard') ? 'active' : ''; ?>" >
              <a href="<?php echo $cfg_baseurl; ?>" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboard">Dashboard</div>
              </a>
            </li>

            <!-- Apps & Pages -->
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Transaksi</span>
            </li>
            <li class="menu-item <?php echo ($page == 'pemesanan') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/pemesanan" class="menu-link">
                <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
                <div data-i18n="Order Sosmed">Order Sosmed</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'tripay') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/tripay" class="menu-link">
                <i class="menu-icon tf-icons ti ti-currency-dollar"></i>
                <div data-i18n="Deposit Instant ⚡">Deposit Instant ⚡</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'manual') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/manualdeposit" class="menu-link">
                <i class="menu-icon tf-icons ti ti-currency-dollar"></i>
                <div data-i18n="Manual Deposit">Manual Deposit</div>
              </a>
            </li>
            

            <!-- Components -->
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Riwayat</span>
            </li>

            <li class="menu-item <?php echo ($page == 'deposit') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/riwayat/deposit" class="menu-link">
                <i class="menu-icon tf-icons ti ti-credit-card"></i>
                <div data-i18n="Riwayat Deposit">Riwayat Deposit</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'depositmanual') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/riwayat/depositmanual" class="menu-link">
                <i class="menu-icon tf-icons ti ti-credit-card"></i>
                <div data-i18n="Riwayat Deposit Manual">Riwayat Deposit Manual</div>
              </a>
            </li>

            <li class="menu-item <?php echo ($page == 'order') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/riwayat/order" class="menu-link">
                <i class="menu-icon tf-icons ti ti-file-invoice"></i>
                <div data-i18n="Riwayat Order">Riwayat Order</div>
              </a>
            </li>


            <!-- Forms & Tables -->
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Halaman</span>
            </li>
            <li class="menu-item <?php echo ($page == 'ticket') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/ticket" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Bantuan">Bantuan</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'harga') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/harga" class="menu-link">
                <i class="menu-icon tf-icons ti ti-file-dollar"></i>
                <div data-i18n="Price List">Price List</div>
              </a>
            </li>
            <!-- Tables -->
            <li class="menu-item <?php echo ($page == 'news') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/news" class="menu-link">
                <i class="menu-icon tf-icons ti ti-world"></i>
                <div data-i18n="News Update">News Update</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'faq') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/faq" class="menu-link">
                <i class="menu-icon tf-icons ti ti-table"></i>
                <div data-i18n="F.A.Q">F.A.Q</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'doc') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/doc" class="menu-link">
                <i class="menu-icon tf-icons ti ti-code"></i>
                <div data-i18n="API">API</div>
              </a>
            </li>
            <?php
                    if ($data_user['level'] == "Developers") {
                    ?>
            <li class="menu-item">
              <a href="<?php echo $cfg_baseurl; ?>/admin" class="menu-link">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="Admin Dashboard">Admin Dashboard</div>
              </a>
            </li>        
            <?php
                    }
                    ?>
            <li class="menu-item">
              <a href="<?php echo $cfg_baseurl; ?>/keluar" class="menu-link">
                <i class="menu-icon tf-icons ti ti-logout"></i>
                <div data-i18n="Keluar">Keluar</div>
              </a>
            </li>

            
          </ul>
        </aside>