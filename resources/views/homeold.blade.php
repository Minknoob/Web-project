
@extends('layouts.app')

@section('title', 'My Notes')

@section('navbar')
    <div class="theme-switch-wrapper">
        <span class="theme-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
            </svg>

        </span>

        <div class="form-check form-switch m-0">
            <input
                class="form-check-input theme-switch"
                type="checkbox"
                role="switch"
                id="themeSwitch"
            >
        </div>

        <span class="theme-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
            </svg>

        </span>
    </div>

    <div class="dropdown">
        <button class="btn btn-gc-outline rounded-pill d-flex align-items-center gap-2 px-3 py-2"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">

            <span class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                  style="width: 30px; height: 30px; background: #c8ff19; color: #080a0e;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>

            <span class="text-light">
                {{ auth()->user()->name }}
            </span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end"
            data-bs-theme="dark">

            <li>
                <span class="dropdown-item-text text-secondary small">
                    {{ auth()->user()->email }}
                </span>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        Sign out
                    </button>
                </form>
            </li>
        </ul>
    </div>
@endsection

@section('content')

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
@endphp

<div class="container-fluid px-3 px-md-4 px-xl-5 py-4"
     data-bs-theme="dark">

    {{-- Verification Alert --}}
    @if (is_null(auth()->user()->email_verified_at))
        <div class="alert bg-dark border border-warning text-light rounded-3 d-flex flex-wrap align-items-center gap-2 mb-4"
             role="status"
             id="verificationAlert">

            <span class="text-warning fw-semibold">
                Account not verified
            </span>

            <span class="text-secondary small">
                — Please verify your email to keep your account secure.
            </span>

            <form action="{{ route('verification.send') }}" method="POST" class="ms-auto">
                @csrf
                <button type="submit"
                        class="btn btn-sm btn-gc-outline rounded-pill">
                    Verify now
                </button>
            </form>

            <button type="button"
                    class="btn-close btn-close-white alert-close"
                    aria-label="Close">
            </button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Desktop Sidebar --}}
        <aside class="col-lg-3 col-xl-2 d-none d-lg-block">
            <div class="card bg-dark border border-secondary rounded-4 sticky-top"
                 style="top: 100px;">

                <div class="card-body p-3">

                    <p class="text-secondary small fw-bold mb-3">
                        YOUR WORKSPACE
                    </p>

                    <button class="sidebar-link is-selected btn btn-dark w-100 text-start mb-2"
                            type="button"
                            data-filter="all">
                        <span>All notes</span>
                        <span class="badge bg-secondary ms-2">{{ count($notes) }}</span>
                    </button>

                    <button class="sidebar-link btn btn-dark w-100 text-start mb-4"
                            type="button"
                            data-filter="shared">
                        Shared with me
                    </button>

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="text-secondary small fw-bold mb-0">
                            YOUR LABELS
                        </p>

                        <button class="btn btn-sm btn-outline-secondary tiny-icon-button"
                                type="button"
                                aria-label="Add label">
                            +
                        </button>
                    </div>

                    <div class="label-list">
                        @foreach ($labelCounts as $label => $count)
                            <button class="sidebar-link label-filter btn btn-dark w-100 text-start mb-1"
                                    type="button"
                                    data-filter-label="{{ $label }}">

                                <span>{{ $label }}</span>
                                <span class="badge bg-secondary ms-2">{{ $count }}</span>
                            </button>
                        @endforeach
                    </div>

                    <button class="sidebar-link manage-labels btn btn-dark w-100 text-start mt-3">
                        Manage labels
                    </button>

                    <hr class="border-secondary my-4">

                    <p class="text-secondary small mb-0">
                        A good idea can start anywhere.
                    </p>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="col-12 col-lg-9 col-xl-10">

            {{-- Toolbar --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

                <div>
                    <p class="text-secondary small fw-semibold mb-1">
                        YOUR IDEAS, ALL IN ONE PLACE
                    </p>

                    <h2 class="text-light fw-bold mb-0" id="notesHeading">
                        My Notes
                        <span class="text-secondary fw-normal"
                              id="activeFilterLabel">
                            recent
                        </span>
                    </h2>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">

                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-secondary">
                            Search
                        </span>

                        <input id="noteSearch"
                               type="search"
                               class="form-control bg-dark text-light border-secondary"
                               placeholder="Search your notes..."
                               autocomplete="off"
                               aria-label="Search notes">
                    </div>

                    <div class="btn-group view-switch"
                         role="group"
                         aria-label="Note display mode">

                        <button class="btn btn-outline-secondary view-option is-active"
                                type="button"
                                data-view="grid"
                                aria-label="Grid view"
                                aria-pressed="true">
                            Grid
                        </button>

                        <button class="btn btn-outline-secondary view-option"
                                type="button"
                                data-view="list"
                                aria-label="List view"
                                aria-pressed="false">
                            List
                        </button>
                    </div>

                    <button class="btn btn-gc-primary create-note-trigger"
                            type="button">
                        + New Note
                    </button>
                </div>
            </div>

            {{-- Mobile Filters --}}
            <div class="d-lg-none mb-4">
                <button class="btn btn-gc-outline w-100"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#mobileSidebar">
                    Browse categories
                </button>
            </div>

            {{-- Notes --}}
            <div class="notes-results" id="notesResults" data-view="grid">

                @foreach ([['name' => 'Pinned', 'notes' => $pinnedNotes, 'key' => 'pinned'], ['name' => 'Other Notes', 'notes' => $otherNotes, 'key' => 'other']] as $group)

                    <section class="note-group mb-5"
                             data-group="{{ $group['key'] }}"
                             aria-label="{{ $group['name'] }}">

                        <div class="d-flex align-items-center gap-3 mb-3">
                            <h3 class="text-light fs-6 fw-semibold mb-0">
                                {{ $group['name'] }}
                            </h3>

                            <div class="flex-grow-1 border-bottom border-secondary"></div>
                        </div>

                        <div class="note-grid row g-3">

                            @foreach ($group['notes'] as $note)

                                <article class="note-card col-12 col-md-6 col-xl-4"
                                         data-note-id="{{ $note['id'] }}"
                                         data-search="{{ $note['title'] }} {{ $note['content'] }}"
                                         data-labels="{{ implode('|', $note['labels']) }}"
                                         data-shared="{{ $note['shared'] ? 'true' : 'false' }}"
                                         data-pinned="{{ $note['pinned'] ? 'true' : 'false' }}">

                                    <div class="card bg-dark border border-secondary rounded-4 h-100 note-card-content">

                                        @if ($note['image'])
                                            <div class="note-thumbnail note-thumbnail-{{ $note['imageStyle'] }} rounded-top-4 p-4">
                                                <span class="text-light small fw-semibold">
                                                    {{ $note['imageStyle'] === 'blue' ? 'COLOR & INSPIRATION' : 'MOMENTS' }}
                                                </span>
                                            </div>
                                        @endif

                                        <div class="card-body d-flex flex-column">

                                            <div class="note-meta-icons d-flex gap-2 mb-3">

                                                @if ($note['pinned'])
                                                    <span class="badge rounded-pill"
                                                          style="background: #c8ff19; color: #080a0e;">
                                                        Pinned
                                                    </span>
                                                @endif

                                                @if ($note['shared'])
                                                    <span class="badge bg-secondary">
                                                        Shared
                                                    </span>
                                                @endif

                                                @if ($note['locked'])
                                                    <span class="badge bg-secondary">
                                                        Private
                                                    </span>
                                                @endif

                                            </div>

                                            <div class="note-copy flex-grow-1">
                                                <h3 class="note-title text-light fs-5 fw-semibold mb-2">
                                                    {{ $note['title'] }}
                                                </h3>

                                                <p class="note-excerpt text-secondary small mb-3">
                                                    {{ $note['locked']
                                                        ? 'Nội dung đã được ẩn để bảo vệ riêng tư.'
                                                        : $note['content'] }}
                                                </p>
                                            </div>

                                            <div class="note-footer mt-auto">

                                                <div class="note-labels d-flex flex-wrap gap-2 mb-3">
                                                    @foreach ($note['labels'] as $label)
                                                        <span class="note-label badge bg-black border border-secondary text-secondary fw-normal">
                                                            {{ $label }}
                                                        </span>
                                                    @endforeach
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="note-updated text-secondary small">
                                                        {{ $note['updated'] }}
                                                    </span>

                                                    <div class="note-hover-actions d-flex gap-1">

                                                        <button class="quick-action pin-note btn btn-sm btn-outline-secondary"
                                                                type="button"
                                                                aria-label="{{ $note['pinned'] ? 'Unpin note' : 'Pin note' }}"
                                                                title="{{ $note['pinned'] ? 'Unpin' : 'Pin' }}">
                                                            {{ $note['pinned'] ? 'Unpin' : 'Pin' }}
                                                        </button>

                                                        <button class="quick-action delete-note btn btn-sm btn-outline-danger"
                                                                type="button"
                                                                aria-label="Delete note"
                                                                title="Delete">
                                                            Delete
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </article>

                            @endforeach
                        </div>
                    </section>
                @endforeach

            </div>

            {{-- Empty State --}}
            <div class="empty-state d-none text-center py-5"
                 id="emptyState">

                <h3 class="text-light mb-2" id="emptyTitle">
                    No notes found
                </h3>

                <p class="text-secondary mb-4" id="emptyDescription">
                    Try another keyword or create your first note.
                </p>

                <button class="btn btn-gc-primary create-note-trigger"
                        type="button">
                    + Create Note
                </button>
            </div>

            <footer class="text-center text-secondary small py-4">
                Your ideas deserve to be remembered.
            </footer>

        </main>
    </div>
</div>

{{-- Mobile Sidebar --}}
<div class="offcanvas offcanvas-start bg-dark mobile-sidebar"
     tabindex="-1"
     id="mobileSidebar"
     aria-labelledby="mobileSidebarTitle"
     data-bs-theme="dark">

    <div class="offcanvas-header border-bottom border-secondary">
        <h2 class="offcanvas-title text-light fs-5 fw-bold"
            id="mobileSidebarTitle">
            Categories
        </h2>

        <button type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas"
                aria-label="Close">
        </button>
    </div>

    <div class="offcanvas-body">

        <p class="text-secondary small fw-bold mb-3">
            YOUR WORKSPACE
        </p>

        <button class="sidebar-link is-selected mobile-filter btn btn-dark w-100 text-start mb-2"
                type="button"
                data-filter="all"
                data-bs-dismiss="offcanvas">
            All notes
            <span class="badge bg-secondary ms-2">{{ count($notes) }}</span>
        </button>

        <button class="sidebar-link mobile-filter btn btn-dark w-100 text-start mb-4"
                type="button"
                data-filter="shared"
                data-bs-dismiss="offcanvas">
            Shared with me
        </button>

        <p class="text-secondary small fw-bold mb-3">
            YOUR LABELS
        </p>

        @foreach ($labelCounts as $label => $count)
            <button class="sidebar-link label-filter mobile-filter btn btn-dark w-100 text-start mb-1"
                    type="button"
                    data-filter-label="{{ $label }}"
                    data-bs-dismiss="offcanvas">

                {{ $label }}
                <span class="badge bg-secondary ms-2">{{ $count }}</span>
            </button>
        @endforeach

        <button class="sidebar-link manage-labels btn btn-dark w-100 text-start mt-3"
                type="button">
            Manage labels
        </button>
    </div>
</div>

{{-- Create Note Modal --}}
<div class="modal fade"
     id="editorModal"
     tabindex="-1"
     aria-labelledby="editorTitle"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered editor-dialog">

        <div class="modal-content bg-dark border border-secondary rounded-4 editor-modal"
             data-bs-theme="dark">

            <div class="modal-header border-secondary">
                <h2 class="modal-title text-light fs-5 fw-bold"
                    id="editorTitle">
                    Create New Note
                </h2>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <label class="visually-hidden" for="editorNoteTitle">
                    Note title
                </label>

                <input class="form-control bg-black text-light border-secondary mb-3"
                       id="editorNoteTitle"
                       type="text"
                       placeholder="Title">

                <label class="visually-hidden" for="editorNoteContent">
                    Note content
                </label>

                <textarea class="form-control bg-black text-light border-secondary"
                          id="editorNoteContent"
                          rows="7"
                          placeholder="Start writing your thoughts..."></textarea>
            </div>

            <div class="modal-footer border-secondary">
                <span class="autosave-status text-secondary small">
                    Auto save
                </span>

                <span class="autosave-time text-secondary small"
                      id="autosaveStatus"
                      aria-live="polite">
                    Draft on this device
                </span>
            </div>

        </div>
    </div>
</div>

{{-- Delete Note Modal --}}
<div class="modal fade"
     id="deleteModal"
     tabindex="-1"
     aria-labelledby="deleteTitle"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered delete-dialog">

        <div class="modal-content bg-dark border border-secondary rounded-4 delete-modal"
             data-bs-theme="dark">

            <div class="modal-body text-center p-4">

                <h2 class="text-light fs-4 fw-bold mb-3"
                    id="deleteTitle">
                    Delete this note?
                </h2>

                <p class="text-secondary">
                    This note will be removed from this list.
                </p>

                <div class="delete-actions d-flex justify-content-center gap-2 mt-4">

                    <button class="btn btn-outline-secondary"
                            type="button"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button class="btn btn-danger"
                            type="button"
                            id="confirmDelete">
                        Delete Note
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Existing JavaScript --}}
<script src="{{ asset('js/homecopy.js') }}" defer></script>

@endsection
