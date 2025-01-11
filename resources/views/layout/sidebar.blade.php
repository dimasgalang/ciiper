<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/chutex.svg') }}" style="width: 40px;">
        </div>
        <div class="sidebar-brand-text mx-3">CIIPER <sup>Sys</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin"
            aria-expanded="true" aria-controls="collapseAdmin">
            <i class="fas fa-fw fa-cog"></i>
            <span>Admin Panel</span>
        </a>
        <div id="collapseAdmin" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('listuser') }}">List User</a>
                <a class="collapse-item" href="{{ route('register.create') }}">Daftar User</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKaryawan"
            aria-expanded="true" aria-controls="collapseKaryawan">
            <i class="fas fa-fw fa-users"></i>
            <span>Employee</span>
        </a>
        <div id="collapseKaryawan" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('karyawan.daftar') }}">Daftar Karyawan</a>
            </div>
        </div>
    </li>
    
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster"
            aria-expanded="true" aria-controls="collapseMaster">
            <i class="fas fa-fw fa-cog"></i>
            <span>Master</span>
        </a>
        <div id="collapseMaster" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('buyer.index') }}">Buyer</a>
                <a class="collapse-item" href="{{ route('brand.index') }}">Brand</a>
                <a class="collapse-item" href="{{ route('style.index') }}">Style</a>
                <a class="collapse-item" href="{{ route('season.index') }}">Season</a>
                <a class="collapse-item" href="{{ route('po.index') }}">Purchase Order</a>
                <a class="collapse-item" href="{{ route('fabrication.index') }}">Fabrication</a>
                <a class="collapse-item" href="{{ route('fabricmill.index') }}">Fabric Mill</a>
                <a class="collapse-item" href="{{ route('factory.index') }}">Factory</a>
            </div>
        </div>
    </li>
    
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrder"
            aria-expanded="true" aria-controls="collapseOrder">
            <i class="fas fa-fw fa-cog"></i>
            <span>Order</span>
        </a>
        <div id="collapseOrder" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('ordermaster.index') }}">Order Master</a>
                <a class="collapse-item" href="{{ route('orderlist.index') }}">Order List</a>
                <a class="collapse-item" href="{{ route('rafproduction.index') }}">RAF Production</a>
            </div>
        </div>
    </li>

    
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFinger"
            aria-expanded="true" aria-controls="collapseFinger">
            <i class="fas fa-fw fa-fingerprint"></i>
            <span>Fingerprint</span>
        </a>
        <div id="collapseFinger" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('fingerprint.tarik-data') }}">Tarik Data</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTelegram"
            aria-expanded="true" aria-controls="collapseTelegram">
            <i class="fas fa-fw fa-address-book"></i>
            <span>Telegram</span>
        </a>
        <div id="collapseTelegram" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('telegram.message') }}">Message History</a>
                <a class="collapse-item" href="{{ route('telegram.index') }}">Send Message</a>
                <a class="collapse-item" href="{{ route('telegram.indexblast') }}">Send Blast</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTemplate"
            aria-expanded="true" aria-controls="collapseTemplate">
            <i class="fas fa-fw fa-file-code"></i>
            <span>Template</span>
        </a>
        <div id="collapseTemplate" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('template.slipgaji') }}">Slip Gaji</a>
            </div>
        </div>
    </li>
     -->
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseExport"
            aria-expanded="true" aria-controls="collapseExport">
            <i class="fas fa-fw fa-upload"></i>
            <span>Export</span>
        </a>
        <div id="collapseExport" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('template.generateslip') }}">Slip Gaji</a>
            </div>
        </div>
    </li> -->

    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseModul"
            aria-expanded="true" aria-controls="collapseModul">
            <i class="fas fa-fw fa-file-pdf"></i>
            <span>Dokumen</span>
        </a>
        <div id="collapseModul" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('modul.daftar') }}">Modul</a>
            </div>
        </div>
    </li>
     -->
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseServices"
            aria-expanded="true" aria-controls="collapseServices">
            <i class="fas fa-fw fa-globe"></i>
            <span>Services</span>
        </a>
        <div id="collapseServices" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="http://36.67.13.11:8018/login" target="_blank">IT Inventory</a>
                <a class="collapse-item" href="http://36.67.13.11:8018/admin" target="_blank">IT Inventory - Admin</a>
                <a class="collapse-item" href="http://36.66.191.116:5432/doc/page/login.asp?_1701438289643" target="_blank">CCTV HikVision</a>
            </div>
        </div>
    </li> -->
    <li class="nav-item">
        <a class="nav-link" href="/docs/api" target="_blank">
            <i class="fas fa-fw fa-table"></i>
            <span>API Docs</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->