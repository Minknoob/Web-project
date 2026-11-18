import './bootstrap';

$(function () {
    const $root = $('html');
    const $results = $('#notesResults');
    const $search = $('#noteSearch');
    const $empty = $('#emptyState');
    const viewKey = 'noteapp-homecopy-view';
    const themeKey = 'noteapp-homecopy-theme';
    const draftKey = 'noteapp-homecopy-draft';
    let activeFilter = 'all';
    let activeLabel = '';
    let cardToDelete = null;
    let searchTimer;
    let saveTimer;

    function setTheme(theme) {
        $root.attr('data-bs-theme', theme);
        const isLight = theme === 'light';
        $('#themeToggle')
            .attr('aria-label', isLight ? 'Change to dark theme' : 'Change to light theme')
            .find('.moon-icon').toggleClass('d-none', !isLight);
        
        $('#themeToggle')
            .find('.sun-icon').toggleClass('d-none', isLight);
        
        $('meta[name="theme-color"]').attr('content', isLight ? '#f5f6f2' : '#080a0e');
        try {
            localStorage.setItem(themeKey, theme);
        } catch (error) {
            console.warn('Không thể lưu giao diện đã chọn.', error);
        }
    }

    function setView(view) {
        const selectedView = view === 'list' ? 'list' : 'grid';
        $results.attr('data-view', selectedView);
        $('.view-option').each(function () {
            const selected = $(this).data('view') === selectedView;
            $(this).toggleClass('is-active', selected).attr('aria-pressed', selected ? 'true' : 'false');
        });
        try {
            localStorage.setItem(viewKey, selectedView);
        } catch (error) {
            console.warn('Không thể lưu kiểu hiển thị đã chọn.', error);
        }
    }

    function updateEmptyState() {
        const isEmpty = $results.find('.note-card[data-filter-visible="true"]').length === 0;
        $empty.toggleClass('d-none', !isEmpty);
        $results.toggleClass('d-none', isEmpty);
        if (isEmpty) {
            const hasSearch = String($search.val() || '').trim().length > 0;
            $('#emptyTitle').text(hasSearch ? 'Chưa tìm thấy ghi chú' : 'Chưa có ghi chú nào');
            $('#emptyDescription').text(hasSearch
                ? 'Thử từ khóa khác hoặc chọn một nhãn để tìm nhanh hơn.'
                : 'Bắt đầu viết điều đầu tiên để lưu lại ý tưởng của bạn nhé.');
        }
    }

    function filterNotes() {
        const query = String($search.val() || '').trim().toLocaleLowerCase('vi');
        $results.find('.note-card').each(function () {
            const $card = $(this);
            const searchable = String($card.data('search') || '').toLocaleLowerCase('vi');
            const labels = String($card.data('labels') || '').split('|');
            const matchesSearch = !query || searchable.includes(query);
            const matchesFilter = activeFilter !== 'shared' || $card.data('shared') === true || $card.attr('data-shared') === 'true';
            const matchesLabel = !activeLabel || labels.includes(activeLabel);
            const matches = matchesSearch && matchesFilter && matchesLabel;
            $card.attr('data-filter-visible', matches ? 'true' : 'false').toggle(matches);
        });
        $results.find('.note-group').each(function () {
            $(this).toggle($(this).find('.note-card[data-filter-visible="true"]').length > 0);
        });
        updateEmptyState();
    }

    function setFilter(filter, label) {
        activeFilter = filter || 'all';
        activeLabel = label || '';
        $('.sidebar-link[data-filter], .sidebar-link[data-filter-label]').removeClass('is-selected');
        if (activeLabel) {
            $('.sidebar-link[data-filter-label]').filter(function () {
                return $(this).attr('data-filter-label') === activeLabel;
            }).addClass('is-selected');
            $('#activeFilterLabel').text(activeLabel);
        } else {
            $('.sidebar-link[data-filter="' + activeFilter + '"]').addClass('is-selected');
            $('#activeFilterLabel').text(activeFilter === 'shared' ? 'được chia sẻ' : 'gần đây');
        }
        filterNotes();
    }

    function moveCardToGroup($card) {
        const isPinned = $card.attr('data-pinned') === 'true';
        const target = isPinned ? 'pinned' : 'other';
        $card.appendTo($results.find('.note-group[data-group="' + target + '"] .note-grid'));
        $results.find('.note-group').each(function () {
            $(this).toggle($(this).find('.note-card').length > 0);
        });
        filterNotes();
    }

    $('.view-option').on('click', function () {
        setView($(this).data('view'));
    });

    $('#themeToggle').on('click', function () {
        setTheme($root.attr('data-bs-theme') === 'light' ? 'dark' : 'light');
    });

    $('.sidebar-link[data-filter]').on('click', function () {
        setFilter($(this).attr('data-filter'), '');
    });

    $('.label-filter').on('click', function () {
        setFilter('all', $(this).attr('data-filter-label'));
    });

    $search.on('input', function () {
        window.clearTimeout(searchTimer);
        searchTimer = window.setTimeout(filterNotes, 300);
    });

    $(document).on('keydown', function (event) {
        if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
            event.preventDefault();
            $search.trigger('focus');
        }
    });

    $('#verificationAlert .alert-close').on('click', function () {
        $('#verificationAlert').addClass('d-none');
    });

    $('.pin-note').on('click', function () {
        const $card = $(this).closest('.note-card');
        const isPinned = $card.attr('data-pinned') === 'true';
        $card.attr('data-pinned', isPinned ? 'false' : 'true');
        $card.find('.pin-note').attr('aria-label', isPinned ? 'Ghim ghi chú' : 'Bỏ ghim ghi chú');
        $card.find('.pin-note .bi').toggleClass('bi-pin-angle bi-pin-angle-fill', !isPinned);
        $card.find('.note-meta-icons .status-pinned').remove();
        if (!isPinned) {
            $card.find('.note-meta-icons').prepend('<span class="status-icon status-pinned" title="Đã ghim" aria-label="Đã ghim"><i class="bi bi-pin-angle-fill"></i></span>');
        }
        moveCardToGroup($card);
    });

    $('.delete-note').on('click', function () {
        cardToDelete = $(this).closest('.note-card');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal')).show();
    });

    $('#confirmDelete').on('click', function () {
        if (cardToDelete) {
            cardToDelete.remove();
            cardToDelete = null;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal')).hide();
            filterNotes();
        }
    });

    $('.create-note-trigger').on('click', function () {
        const draft = readDraft();
        $('#editorNoteTitle').val(draft.title || '');
        $('#editorNoteContent').val(draft.content || '');
        $('#autosaveStatus').text(draft.title || draft.content ? 'Đã lưu trên thiết bị này' : 'Bản nháp trên thiết bị này');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('editorModal')).show();
    });

    function readDraft() {
        try {
            return JSON.parse(localStorage.getItem(draftKey) || '{}');
        } catch (error) {
            console.warn('Không thể đọc bản nháp đã lưu.', error);
            return {};
        }
    }

    function saveDraft() {
        const draft = {
            title: $('#editorNoteTitle').val(),
            content: $('#editorNoteContent').val()
        };
        try {
            localStorage.setItem(draftKey, JSON.stringify(draft));
            $('#autosaveStatus').text('Đã lưu trên thiết bị này');
        } catch (error) {
            $('#autosaveStatus').text('Không thể lưu bản nháp');
            console.warn('Không thể lưu bản nháp.', error);
        }
    }

    $('#editorNoteTitle, #editorNoteContent').on('input', function () {
        $('#autosaveStatus').text('Đang lưu...');
        window.clearTimeout(saveTimer);
        saveTimer = window.setTimeout(saveDraft, 400);
    });

    $('#editorModal').on('hidden.bs.modal', function () {
        window.clearTimeout(saveTimer);
        saveDraft();
    });

    try {
        const savedTheme = localStorage.getItem(themeKey);
        const savedView = localStorage.getItem(viewKey);
        if (savedTheme === 'light' || savedTheme === 'dark') {
            setTheme(savedTheme);
        }
        setView(savedView || 'grid');
    } catch (error) {
        console.warn('Không thể đọc tùy chọn giao diện đã lưu.', error);
        setView('grid');
    }

    filterNotes();
});
