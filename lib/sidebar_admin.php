<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.html" class="app-brand-link">
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
              <span class="app-brand-text demo menu-text fw-bold">Adminpanel</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
              <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboards -->



            <li class="menu-item <?php echo ($page == 'beranda') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Beranda">Beranda</div>
              </a>
            </li>

          

            <!-- Apps & Pages -->
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Apps &amp; Pages</span>
            </li>
            <li class="menu-item <?php echo ($page == 'user') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin/user" class="menu-link">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="User">User</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'api') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin/api" class="menu-link">
                <i class="menu-icon tf-icons ti ti-server"></i>
                <div data-i18n="API Layanan">API Layanan</div>
              </a>
            </li>
            
            <li class="menu-item <?php echo ($pages == 'sosmeds') ? 'open' : ''; ?>">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-dollar"></i>
                <div data-i18n="Layanan Sosmed">Layanan Sosmed</div>
                
              </a>
              <ul class="menu-sub">
                <li class="menu-item <?php echo ($page == 'sosmed') ? 'active' : ''; ?>">
                  <a href="<?php echo $cfg_baseurl; ?>/admin/sosmed" class="menu-link">
                    <div data-i18n="Data Sosmed">Data Sosmed</div>
                  </a>
                </li>
                <li class="menu-item <?php echo ($page == 'kategori') ? 'active' : ''; ?>">
                  <a href="<?php echo $cfg_baseurl; ?>/admin/kategori" class="menu-link">
                    <div data-i18n="Kategori Sosmed">Kategori Sosmed</div>
                  </a>
                </li>
                <li class="menu-item <?php echo ($page == 'update') ? 'active' : ''; ?>">
                  <a href="<?php echo $cfg_baseurl; ?>/admin/update" class="menu-link">
                    <div data-i18n="Update Sosmed">Update Sosmed</div>
                  </a>
                </li>
                
              </ul>
            </li>
            
            
            <li class="menu-item <?php echo ($pages == 'transaksi') ? 'open' : ''; ?>">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-receipt"></i>
                <div data-i18n="Transaksi">Transaksi</div>
              </a>
              <ul class="menu-sub">
                
                <li class="menu-item <?php echo ($page == 'deposit') ? 'active' : ''; ?>">
                  <a href="<?php echo $cfg_baseurl; ?>/admin/deposit" class="menu-link">
                    <div data-i18n="Deposit Instant">Deposit Instant</div>
                  </a>
                </li>
                <li class="menu-item <?php echo ($page == 'depositmanual') ? 'active' : ''; ?>">
                  <a href="<?php echo $cfg_baseurl; ?>/admin/depositmanual" class="menu-link">
                    <div data-i18n="Deposit Manual">Deposit Manual</div>
                  </a>
                </li>
                
                <li class="menu-item <?php echo ($page == 'pembelian') ? 'active' : ''; ?>">
                  <a href="<?php echo $cfg_baseurl; ?>/admin/pembelian" class="menu-link">
                    <div data-i18n="Pembelian Sosmed">Pembelian Sosmed</div>
                  </a>
                </li>
                
              </ul>
            </li>
            

            <!-- Components -->
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Pengaturan</span>
            </li>
            <li class="menu-item <?php echo ($page == 'website') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin/website" class="menu-link">
                <i class="menu-icon tf-icons ti ti-world"></i>
                <div data-i18n="Website">Website</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'seo') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin/seo" class="menu-link">
                <i class="menu-icon tf-icons ti ti-target"></i>
                <div data-i18n="SEO">SEO</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'depositmanuals') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin/depositmanuals" class="menu-link">
                <i class="menu-icon tf-icons ti ti-credit-card"></i>
                <div data-i18n="Manual Deposit">Manual Deposit</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'pembayaran') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin/pembayaran" class="menu-link">
                <i class="menu-icon tf-icons ti ti-wallet"></i>
                <div data-i18n="Pembayaran">Pembayaran</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Hapus Layanan</span>
            </li>
            <li class="menu-item <?php echo ($page == 'hapus') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin/hapus"  class="menu-link">
                <i class="menu-icon tf-icons ti ti-trash"></i>
                <div data-i18n="Hapus Semua">Hapus Semua</div>
              </a>
            </li>

            <!-- Misc -->
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Halaman</span>
            </li>
            <li class="menu-item <?php echo ($page == 'slider') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin/slider" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mouse"></i>
                <div data-i18n="Slider Apps">Slider Apps</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'tiket') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin/tiket" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Tiket Bantuan">Tiket Bantuan</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'news') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin/news" class="menu-link">
                <i class="menu-icon tf-icons ti ti-bell"></i>
                <div data-i18n="News">News</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'blog') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin/blog" class="menu-link">
                <i class="menu-icon tf-icons ti ti-pencil"></i>
                <div data-i18n="Blog">Blog</div>
              </a>
            </li>
            <li class="menu-item <?php echo ($page == 'livetv') ? 'active' : ''; ?>">
              <a href="<?php echo $cfg_baseurl; ?>/admin/livetv" class="menu-link">
                <i class="menu-icon tf-icons ti ti-rss"></i>
                <div data-i18n="Live TV">Live TV</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="<?php echo $cfg_baseurl; ?>/" class="menu-link">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="Halaman User">Halaman User</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="<?php echo $cfg_baseurl; ?>/keluar" class="menu-link">
                <i class="menu-icon tf-icons ti ti-lock"></i>
                <div data-i18n="Keluar">Keluar</div>
              </a>
            </li>
          </ul>
        </aside>
        <!-- / Menu -->