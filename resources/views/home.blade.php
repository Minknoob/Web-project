<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#030407">
    <title>Ghi chú của tôi — Không gian ý tưởng của bạn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/home.scss', 'resources/js/home.js'])
    
</head>
<body class="notes-app">
    @php
        $notes = [
            [
                'id' => 1,
                'title' => 'Một ý tưởng nhỏ, một khởi đầu lớn',
                'content' => 'Thiết kế một góc làm việc số thật yên tĩnh. Bắt đầu từ những điều đơn giản: ánh sáng dịu, một tách trà và khoảng trống cho ý tưởng mới.',
                'labels' => ['Ý tưởng', 'Cá nhân'],
                'updated' => '10 phút trước',
                'pinned' => true,
                'shared' => false,
                'locked' => false,
                'image' => false,
            ],
            [
                'id' => 2,
                'title' => 'Danh sách cần nhớ',
                'content' => 'Gọi cho mẹ. Mua hoa cuối tuần. Nhớ mang theo quyển sổ xanh.',
                'labels' => ['Cá nhân'],
                'updated' => 'Hôm nay, 09:42',
                'pinned' => true,
                'shared' => true,
                'locked' => false,
                'image' => false,
            ],
            [
                'id' => 3,
                'title' => 'Lời nhắc cho riêng mình',
                'content' => 'Mỗi bước nhỏ đều đáng được ghi nhận. Hãy dành thời gian nghỉ ngơi và đừng quên tự hào về hành trình của mình.',
                'labels' => ['Riêng tư', 'Cảm hứng'],
                'updated' => 'Hôm qua',
                'pinned' => false,
                'shared' => false,
                'locked' => true,
                'image' => false,
            ],
            [
                'id' => 4,
                'title' => 'Bảng cảm hứng — sắc xanh',
                'content' => 'Một vài sắc thái cho dự án sắp tới: xanh cobalt, màu kem ấm và một chấm vàng chanh thật sáng.',
                'labels' => ['Công việc', 'Thiết kế'],
                'updated' => 'Hôm qua',
                'pinned' => false,
                'shared' => true,
                'locked' => false,
                'image' => true,
                'imageStyle' => 'blue',
                'imageAlt' => 'Bảng màu xanh cobalt và vàng chanh',
            ],
            [
                'id' => 5,
                'title' => 'Góc quán quen',
                'content' => 'Quán nhỏ trong con hẻm có cửa sổ đón nắng chiều, cà phê thơm và chiếc bàn gỗ đủ rộng để mở laptop.',
                'labels' => ['Đời sống', 'Địa điểm'],
                'updated' => 'Thứ Hai',
                'pinned' => false,
                'shared' => false,
                'locked' => false,
                'image' => true,
                'imageStyle' => 'warm',
                'imageAlt' => 'Khung cảnh ấm áp của một góc quán quen',
            ],
            [
                'id' => 6,
                'title' => 'Ý tưởng cho buổi thuyết trình',
                'content' => 'Mở đầu bằng một câu hỏi, kể câu chuyện ngắn, rồi để phần minh họa làm công việc của nó. Ít chữ hơn, nhiều khoảng thở hơn.',
                'labels' => ['Công việc', 'Ý tưởng', 'Học tập'],
                'updated' => 'Thứ Hai',
                'pinned' => false,
                'shared' => true,
                'locked' => false,
                'image' => false,
            ],
            [
                'id' => 7,
                'title' => 'Mua sữa',
                'content' => 'Nhớ ghé siêu thị trên đường về.',
                'labels' => ['Cá nhân'],
                'updated' => 'Chủ Nhật',
                'pinned' => false,
                'shared' => false,
                'locked' => false,
                'image' => false,
            ],
            [
                'id' => 8,
                'title' => 'Những điều muốn học',
                'content' => 'Tìm hiểu thêm về nhiếp ảnh phim, học cách làm bánh mì men tự nhiên và thử viết nhật ký mỗi tối. Không cần vội, mỗi lần một chút.',
                'labels' => ['Học tập', 'Cảm hứng'],
                'updated' => 'Thứ Bảy',
                'pinned' => false,
                'shared' => false,
                'locked' => false,
                'image' => false,
            ],
            [
                'id' => 9,
                'title' => 'Mật khẩu két sắt',
                'content' => 'Thông tin riêng tư được bảo vệ trong ghi chú này.',
                'labels' => ['Riêng tư'],
                'updated' => 'Thứ Sáu',
                'pinned' => false,
                'shared' => false,
                'locked' => true,
                'image' => false,
            ],
        ];
        $pinnedNotes = array_values(array_filter($notes, fn ($note) => $note['pinned']));
        $otherNotes = array_values(array_filter($notes, fn ($note) => ! $note['pinned']));
        $labelCounts = [];
        foreach ($notes as $note) {
            foreach ($note['labels'] as $label) {
                $labelCounts[$label] = ($labelCounts[$label] ?? 0) + 1;
            }
        }
        ksort($labelCounts);

        $user = Auth::user();
    @endphp

    <nav class="topbar" aria-label="Điều hướng chính">
        <div class="topbar-brand">
            
            {{-- Sidebar Button --}}
            <button class="icon-button mobile-menu-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Mở danh mục">
                
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="25" height="25" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>

            </button>

            {{-- Brand --}}
            <a class="d-inline-flex align-items-center gap-2"
               href="{{ url('/') }}"
               aria-label="GhiChú - Trang chủ">

                {{-- Brand Icon --}}
                <span class="gc-brand-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24"
                         fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 4.5h9.5L20 8v11.5H7V4.5Z"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linejoin="round"/>
                        <path d="M16 4.8V8h3.2M4 8h2M4 12h2M4 16h2M10 12h6M10 15.5h6"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linecap="round"/>
                    </svg>
                </span>

                {{-- Brand Name --}}
                <span class="gc-brand">Note<span>App</span></span>
            </a>
        </div>

        {{-- Search Box --}}
        <label class="search-box" for="noteSearch">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>

            <input id="noteSearch" type="search" placeholder="Search your notes here..." autocomplete="off" aria-label="Note Search">
        </label>

        <div class="topbar-actions">

            {{-- Change Layout --}}
            <div class="view-switch" role="group" aria-label="Kiểu hiển thị ghi chú">
                <button class="icon-button view-option is-active" type="button" data-view="grid" aria-label="Show in grid view" aria-pressed="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>

                </button>

                <button class="icon-button view-option" type="button" data-view="list" aria-label="Show in list view" aria-pressed="false">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </button>
            </div>

            {{-- Change Theme --}}
            <button id="themeToggle" class="icon-button theme-toggle" type="button" aria-label="Chuyển sang giao diện sáng" title="Đổi giao diện">
                
                <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" fill="none" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                </svg>

                <svg class="moon-icon d-none" xmlns="http://www.w3.org/2000/svg" fill="none" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                </svg>


            </button>

            {{-- Username and Avatar --}}
            <div class="dropdown">
                <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User Profile">
                    
                    <div class="profile-avatar-wrapper">
                        <span 
                            class="{{ $user->avatar ? 'd-none' : '' }} profile-avatar profile-avatar-fallback
                                    rounded-circle d-flex align-items-center justify-content-center fw-bold"
                            style="background: #c8ff19; color: #080a0e;">
                            
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>

                        <img
                            src="{{ $user->avatar
                                ? asset('storage/' . $user->avatar)
                                : '' }}"
                            alt="User avatar"
                            class="profile-avatar"
                            style="{{ $user->avatar ? '' : 'display:none' }}"
                        >
                    </div>
                    
                    {{-- <span class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                        style="width: 30px; height: 30px; background: #c8ff19; color: #080a0e;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span> --}}

                    <span class="username">
                        {{ auth()->user()->name }}
                    </span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end profile-menu">
                    {{-- Email --}}
                    <li>
                        <span class="dropdown-item" style="color:#999999">
                            {{ auth()->user()->email }}
                        </span>
                    </li>
                    
                    {{-- User Profile --}}
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>

                            Profile
                        </a>
                    </li>


                    <li>
                        <a class="dropdown-item" href="#">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>

                            Setting
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    {{-- Sign Out --}}
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                </svg>
    
                                Sign out
                            </button>
                        </form>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    {{-- Email Verify Notification --}}
    <div class="verification-alert" role="status" id="verificationAlert">
        <span class="alert-icon">
            <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>

        </span>
        
        <span>
            <strong>Your email is not verified yet</strong>
            
            <span class="alert-detail"> 
                — Verify email to protect your notes.
            </span>
        </span>
        
        <form class="verification-form" action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="alert-link">
                Verify now
                
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="12" height="15" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                </svg>
            </button>
            

        </form>
        
    </div>

    <div class="workspace">
        <aside class="sidebar sidebar-desktop" aria-label="Danh mục ghi chú">
            <div class="sidebar-inner">
                <p class="sidebar-caption">KHÔNG GIAN CỦA BẠN</p>
                <button class="sidebar-link is-selected" type="button" data-filter="all">
                    <i class="bi bi-journal-text"></i><span>Tất cả note</span><span class="sidebar-count">{{ count($notes) }}</span>
                </button>
                <button class="sidebar-link" type="button" data-filter="shared">
                    <i class="bi bi-people"></i><span>Được chia sẻ với tôi</span>
                </button>

                <div class="sidebar-section-heading">
                    <p class="sidebar-caption">NHÃN CỦA BẠN</p>
                    <button class="tiny-icon-button" type="button" aria-label="Thêm nhãn"><i class="bi bi-plus"></i></button>
                </div>
                <div class="label-list">
                    @foreach ($labelCounts as $label => $count)
                        <button class="sidebar-link label-filter" type="button" data-filter-label="{{ $label }}">
                            <span class="label-dot label-dot-{{ $loop->index % 5 }}"></span>
                            <span>{{ $label }}</span><span class="sidebar-count">{{ $count }}</span>
                        </button>
                    @endforeach
                </div>
                <button class="sidebar-link manage-labels" type="button">
                    <i class="bi bi-sliders2"></i><span>Quản lý nhãn</span>
                </button>
            </div>
        </aside>

        <div class="offcanvas offcanvas-start mobile-sidebar" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarTitle">
            <div class="offcanvas-header">
                <h2 class="offcanvas-title brand-mark" id="mobileSidebarTitle"><span class="brand-icon"><i class="bi bi-journal-bookmark-fill"></i></span> ghi<span class="brand-accent">chú</span></h2>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Đóng danh mục"></button>
            </div>
            <div class="offcanvas-body">
                <p class="sidebar-caption">KHÔNG GIAN CỦA BẠN</p>
                <button class="sidebar-link is-selected mobile-filter" type="button" data-filter="all" data-bs-dismiss="offcanvas"><i class="bi bi-journal-text"></i><span>Tất cả note</span><span class="sidebar-count">{{ count($notes) }}</span></button>
                <button class="sidebar-link mobile-filter" type="button" data-filter="shared" data-bs-dismiss="offcanvas"><i class="bi bi-people"></i><span>Được chia sẻ với tôi</span></button>
                <div class="sidebar-section-heading"><p class="sidebar-caption">NHÃN CỦA BẠN</p></div>
                @foreach ($labelCounts as $label => $count)
                    <button class="sidebar-link label-filter mobile-filter" type="button" data-filter-label="{{ $label }}" data-bs-dismiss="offcanvas">
                        <span class="label-dot label-dot-{{ $loop->index % 5 }}"></span><span>{{ $label }}</span><span class="sidebar-count">{{ $count }}</span>
                    </button>
                @endforeach
                <button class="sidebar-link manage-labels" type="button"><i class="bi bi-sliders2"></i><span>Quản lý nhãn</span></button>
            </div>
        </div>

        <main class="main-content">
            <section class="notes-section" aria-labelledby="notesHeading">
                <div class="section-toolbar">
                    <div>
                        <p class="eyebrow section-eyebrow">BẢN ĐỒ Ý TƯỞNG CỦA BẠN</p>
                        <h2 id="notesHeading">Ghi chú <span id="activeFilterLabel">gần đây</span><span class="section-period">,</span></h2>
                    </div>
                    <button class="button-quiet sort-button" type="button" aria-label="Sắp xếp ghi chú">
                        <i class="bi bi-arrow-down-up"></i><span>Mới cập nhật</span>
                    </button>
                </div>

                <div class="notes-results" id="notesResults" data-view="grid">
                    @foreach ([['name' => 'Đã ghim', 'notes' => $pinnedNotes, 'key' => 'pinned'], ['name' => 'Khác', 'notes' => $otherNotes, 'key' => 'other']] as $group)
                        <section class="note-group" data-group="{{ $group['key'] }}" aria-label="{{ $group['name'] }}">
                            <h3 class="group-title"><span>{{ $group['name'] }}</span><span class="group-rule"></span></h3>
                            <div class="note-grid">
                                @foreach ($group['notes'] as $note)
                                    <article class="note-card{{ $note['locked'] ? ' note-card-locked' : '' }}" data-note-id="{{ $note['id'] }}" data-search="{{ $note['title'] }} {{ $note['content'] }}" data-labels="{{ implode('|', $note['labels']) }}" data-shared="{{ $note['shared'] ? 'true' : 'false' }}" data-pinned="{{ $note['pinned'] ? 'true' : 'false' }}">
                                        @if ($note['image'])
                                            <div class="note-thumbnail note-thumbnail-{{ $note['imageStyle'] }}" role="img" aria-label="{{ $note['imageAlt'] }}">
                                                <span class="thumbnail-orb"></span><span class="thumbnail-caption">{{ $note['imageStyle'] === 'blue' ? 'MÀU SẮC & CẢM HỨNG' : 'NHỮNG KHOẢNH KHẮC' }}</span>
                                            </div>
                                        @endif
                                        <div class="note-card-content">
                                            <div class="note-meta-icons" aria-label="Trạng thái ghi chú">
                                                @if ($note['pinned'])<span class="status-icon status-pinned" title="Đã ghim" aria-label="Đã ghim"><i class="bi bi-pin-angle-fill"></i></span>@endif
                                                @if ($note['shared'])<span class="status-icon" title="Đã chia sẻ" aria-label="Đã chia sẻ"><i class="bi bi-people-fill"></i></span>@endif
                                                @if ($note['locked'])<span class="status-icon lock-status" title="Được khóa" aria-label="Được khóa"><i class="bi bi-lock-fill"></i></span>@endif
                                            </div>
                                            <div class="note-copy">
                                                <h3 class="note-title">{{ $note['title'] }}</h3>
                                                <p class="note-excerpt">{{ $note['locked'] ? 'Nội dung đã được ẩn để bảo vệ riêng tư.' : $note['content'] }}</p>
                                            </div>
                                            <div class="note-footer">
                                                <div class="note-labels">
                                                    @foreach ($note['labels'] as $label)
                                                        <span class="note-label">{{ $label }}</span>
                                                    @endforeach
                                                </div>
                                                <span class="note-updated">{{ $note['updated'] }}</span>
                                            </div>
                                            <div class="note-hover-actions">
                                                <button class="quick-action pin-note" type="button" aria-label="{{ $note['pinned'] ? 'Bỏ ghim ghi chú' : 'Ghim ghi chú' }}" title="{{ $note['pinned'] ? 'Bỏ ghim' : 'Ghim' }}"><i class="bi {{ $note['pinned'] ? 'bi-pin-angle-fill' : 'bi-pin-angle' }}"></i></button>
                                                <button class="quick-action delete-note" type="button" aria-label="Xóa ghi chú" title="Xóa"><i class="bi bi-trash3"></i></button>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
                <div class="empty-state d-none" id="emptyState">
                    <div class="empty-illustration" aria-hidden="true">
                        <div class="empty-sheet"><i class="bi bi-journal-plus"></i><span></span><span></span><span></span></div>
                        <span class="empty-spark">✦</span>
                    </div>
                    <h3 id="emptyTitle">Chưa tìm thấy ghi chú</h3>
                    <p id="emptyDescription">Thử một từ khóa khác hoặc bắt đầu viết điều đầu tiên nhé.</p>
                    <button class="button-primary create-note-trigger" type="button"><i class="bi bi-plus-lg"></i> TẠO NOTE MỚI</button>
                </div>
            </section>
        </main>
    </div>

    <button class="fab create-note-trigger" type="button" aria-label="Tạo ghi chú mới" title="Tạo ghi chú mới"><i class="bi bi-plus-lg"></i></button>

    <div class="modal fade" id="editorModal" tabindex="-1" aria-labelledby="editorTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered editor-dialog">
            <div class="modal-content editor-modal">
                <div class="modal-header">
                    <span class="editor-kicker"><i class="bi bi-journal-plus"></i> GHI CHÚ MỚI</span>
                    <button type="button" class="icon-button" data-bs-dismiss="modal" aria-label="Đóng trình soạn thảo"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <label class="visually-hidden" for="editorNoteTitle">Tiêu đề ghi chú</label>
                    <input class="editor-title-input" id="editorNoteTitle" type="text" placeholder="Tiêu đề">
                    <label class="visually-hidden" for="editorNoteContent">Nội dung ghi chú</label>
                    <textarea class="editor-content-input" id="editorNoteContent" placeholder="Bắt đầu viết điều bạn đang nghĩ..."></textarea>
                </div>
                <div class="modal-footer editor-footer">
                    <span class="autosave-status"><i class="bi bi-cloud-check"></i> Tự động lưu</span>
                    <span class="autosave-time" id="autosaveStatus" aria-live="polite">Bản nháp trên thiết bị này</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered delete-dialog">
            <div class="modal-content delete-modal">
                <div class="modal-body text-center">
                    <span class="delete-symbol"><i class="bi bi-trash3"></i></span>
                    <h2 id="deleteTitle">Xóa ghi chú này?</h2>
                    <p>Ghi chú sẽ được gỡ khỏi danh sách này.</p>
                    <div class="delete-actions">
                        <button class="button-secondary" type="button" data-bs-dismiss="modal">Giữ lại</button>
                        <button class="button-danger" type="button" id="confirmDelete">Xóa ghi chú</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</body>
</html>
