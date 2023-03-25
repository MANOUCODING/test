<template>
    <!-- =======================Header START -->
    <header class="navbar-light navbar-sticky header-static border-bottom navbar-dashboard">
        <!-- Logo Nav START -->
        <nav class="navbar navbar-expand-xl">
            <div class="container">
                <!-- Logo START -->
                <a class="navbar-brand me-3" href="/">
                    <img class="navbar-brand-item light-mode-item" src="/assets/images/logo.png" alt="logo">					
                </a>
                <!-- Logo END -->
    
                
                <!-- Responsive navbar toggler -->
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="text-body h6 d-none d-sm-inline-block">Menu</span>
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Main navbar START -->
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav navbar-nav-scroll mx-auto">

                        <!-- Nav item 1 Demos -->
                        <li class="nav-item"><a class="nav-link" href="/admin/categories"><i class="bi bi-house-door me-1"></i>Mes articles</a></li>

                    </ul>
                    
                    <!-- Search dropdown START -->
                    <div class="nav my-3 my-xl-0 px-4 px-lg-1 flex-nowrap align-items-center">
                        <div class="nav-item w-100">
                            <form class="position-relative">
                                <input class="form-control pe-5 bg-transparent" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn bg-transparent border-0 px-2 py-0 position-absolute top-50 end-0 translate-middle-y" type="submit"><i class="fas fa-search fs-6 "></i></button>
                            </form>
                        </div>
                    </div>
                    <!-- Search dropdown END -->
                </div>
                <!-- Main navbar END -->
              
                <!-- Nav right START -->
                <div class="nav flex-nowrap align-items-center">

                    <!-- Notification dropdown START -->
                    <div class="nav-item ms-2 ms-md-3 dropdown">
                        <!-- Notification button -->
                        <a class="btn btn-primary-soft btn-round mb-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <i class="bi bi-bell fa-fw"></i>
                        </a>
                        <!-- Notification dote -->
                        <span class="notif-badge animation-blink"></span>

                        <!-- Notification dropdown menu START -->
                        <div class="dropdown-menu dropdown-animation dropdown-menu-end dropdown-menu-size-md p-0 shadow-lg border-0">
                            <div class="card bg-transparent">
                                <div class="card-header bg-transparent border-bottom p-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0">Notifications <span class="badge bg-danger bg-opacity-10 text-danger ms-2">2 new</span></h6>
                                    <a class="small" href="#">Clear all</a>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-unstyled list-group-flush">
                                        <!-- Notif item -->
                                        <li>
                                            <a href="#" class="list-group-item-action border-0 border-bottom d-flex p-3">
                                                <div class="me-3">
                                                    <div class="avatar avatar-sm">
                                                        <img class="avatar-img rounded-circle" src="/assets/images/avatar/08.jpg" alt="avatar">
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">12 new members joined</h6>
                                                    <span class="small"> <i class="bi bi-clock"></i> 3 min ago</span>
                                                </div>
                                            </a>
                                        </li>

                                        <!-- Notif item -->
                                        <li>
                                            <a href="#" class="list-group-item-action border-0 border-bottom d-flex p-3">
                                                <div class="me-3">
                                                    <div class="avatar avatar-sm">
                                                        <img class="avatar-img rounded-circle" src="/assets/images/avatar/02.jpg" alt="avatar">
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Larry Lawson deleted account</h6>
                                                    <span class="small"> <i class="bi bi-clock"></i> 6 min ago</span>
                                                </div>
                                            </a>
                                        </li>

                                        <!-- Notif item -->
                                        <li>
                                            <a href="#" class="list-group-item-action border-0 border-bottom d-flex p-3">
                                                <div class="me-3">
                                                    <div class="avatar avatar-sm">
                                                        <img class="avatar-img rounded-circle" src="/assets/images/avatar/05.jpg" alt="avatar">
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Byan commented on your post</h6>
                                                    <span class="small"> <i class="bi bi-clock"></i> 10 min ago</span>
                                                </div>
                                            </a>
                                        </li>

                                        <!-- Notif item -->
                                        <li>
                                            <a href="#" class="list-group-item-action border-0 border-bottom d-flex p-3">
                                                <div class="me-3">
                                                    <div class="avatar avatar-sm">
                                                        <img class="avatar-img rounded-circle" src="/assets/images/avatar/03.jpg" alt="avatar">
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Settings updated</h6>
                                                    <span class="small"> <i class="bi bi-clock"></i> Yesterday</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Button -->
                                <div class="card-footer bg-transparent border-0 py-3 text-center position-relative">
                                    <a href="#" class="stretched-link">See all incoming activity</a>
                                </div>
                            </div>
                        </div>
                        <!-- Notification dropdown menu END -->
                    </div>
                    <!-- Notification dropdown END -->

                    <!-- Profile dropdown START -->
                    <div class="nav-item ms-2 ms-md-3 dropdown">
                        <!-- Avatar -->
                        <a class="avatar avatar-sm p-0" href="#" id="profileDropdown" role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="avatar-img rounded-circle" src="/assets/images/avatar/03.jpg" alt="avatar">
                        </a>

                        <!-- Profile dropdown START -->
                        <ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3" aria-labelledby="profileDropdown">
                            <!-- Profile info -->
                            <li class="px-3">
                                <div class="d-flex align-items-center">
                                    <!-- Avatar -->
                                    <div class="avatar me-3">
                                        <img class="avatar-img rounded-circle shadow" src="/assets/images/avatar/03.jpg" alt="avatar">
                                    </div>
                                    <div>
                                        <a class="h6 mt-2 mt-sm-0" href="#"> Louis Ferguson</a>
                                        <p class="small m-0">example@gmail.com</p>
                                    </div>
                                </div>
                                <hr>
                            </li>
                            <!-- Links -->
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person fa-fw me-2"></i>Edit Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear fa-fw me-2"></i>Account Settings</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-info-circle fa-fw me-2"></i>Help</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-power fa-fw me-2"></i>Sign Out</a></li>
                            <li class="dropdown-divider mb-3"></li>
                            <li>
                                <div class="dropdown-item">
                                    <div class="modeswitch m-0" id="darkModeSwitch">
                                        <div class="switch"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <!-- Profile dropdown END -->
                    </div>
                    <!-- Profile dropdown END -->

                <!-- Nav right END -->
                </div>
            </div>
        </nav>
    </header>
    <!-- =======================Header END -->
    </template>