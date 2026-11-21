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
    let searchResultIds = null;
    let searchRequestId = 0;
    let cardToDelete = null;
    let searchTimer;
    let saveTimer;
    let justSavedNote = false;
    let editingNote = null;
    let autosaveTimer;
    let pendingImageFiles = [];
    let pendingImageUrls = [];
    let imageViewerTrigger = null;

    function noteUrl(templateName, noteId, imageId) {
        let url = $('meta[name="' + templateName + '"]').attr('content')
            .replace('__NOTE_ID__', encodeURIComponent(noteId));
        if (imageId !== undefined) {
            url = url.replace('__IMAGE_ID__', encodeURIComponent(imageId));
        }
        return url;
    }

    function clearPendingImages() {
        pendingImageUrls.forEach(function (url) {
            URL.revokeObjectURL(url);
        });
        pendingImageUrls = [];
        pendingImageFiles = [];
        $('#noteImages').val('');
        $('#noteImagePreview').empty();
        updateImagesFrameVisibility();
    }

    function updateImagesFrameVisibility() {
        const hasImages = $('#existingNoteImages .note-image-item, #noteImagePreview .note-image-item').length > 0;
        $('#noteImagesFrame').toggleClass('d-none', !hasImages);
    }

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
            $

            $('#emptyDescription').text(hasSearch
                ? 'Thử từ khóa khác hoặc chọn một nhãn để tìm nhanh hơn.'
                : 'Bắt đầu viết điều đầu tiên để lưu lại ý tưởng của bạn nhé.');
        }
    }

    function filterNotes() {
        const query = String($search.val() || '').trim().toLocaleLowerCase('vi');
        $results.find('.note-card').each(function () {
            const $card = $(this);
            const labels = String($card.data('labels') || '').split('|');
            const matchesSearch = searchResultIds === null || searchResultIds.has(String($card.data('note-id')));
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
        const query = String($search.val() || '').trim();
        const requestId = ++searchRequestId;
        searchTimer = window.setTimeout(function () {
            axios.get($('meta[name="notes-index-url"]').attr('content'), {
                params: { search: query }
            })
                .then(function (response) {
                    if (requestId !== searchRequestId) {
                        return;
                    }

                    searchResultIds = new Set(
                        response.data.notes.map(function (note) {
                            return String(note.id);
                        })
                    );
                    filterNotes();
                })
                .catch(function (error) {
                    if (requestId !== searchRequestId) {
                        return;
                    }

                    console.error('Không thể tìm kiếm ghi chú.', error);
                    window.alert(error.response?.data?.message || 'Không thể tìm kiếm ghi chú. Vui lòng thử lại.');
                });
        }, 300);
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
        axios.post($card.data('pin-url'))
            .then(function (response) {
                const pinned = response.data.pinned;
                $card.attr('data-pinned', pinned ? 'true' : 'false');
                $card.find('.pin-note').attr('aria-label', pinned ? 'Bỏ ghim ghi chú' : 'Ghim ghi chú');
                $card.find('.pin-note .bi').toggleClass('bi-pin-angle', !pinned).toggleClass('bi-pin-angle-fill', pinned);
                $card.find('.note-meta-icons .status-pinned').remove();
                if (pinned) {
                    $card.find('.note-meta-icons').prepend('<span class="status-icon status-pinned" title="Đã ghim" aria-label="Đã ghim"><i class="bi bi-pin-angle-fill"></i></span>');
                }
                moveCardToGroup($card);
            })
            .catch(function (error) {
                console.error('Không thể cập nhật trạng thái ghim.', error);
                window.alert(error.response?.data?.message || 'Không thể ghim ghi chú.');
            });
    });

    $('.delete-note').on('click', function () {
        cardToDelete = $(this).closest('.note-card');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal')).show();
    });

    function openEditModal($card) {
        editingNote = {
            id: $card.data('note-id'),
            card: $card,
            updateUrl: $card.data('update-url')
        };
        $('#editorModalTitle').text('Edit Note');
        $('#editorNoteTitle').val($card.data('title') || '');
        $('#editorNoteContent').val($card.data('content') || '');
        $('#saveNote').text('Save');
        $('#autosaveStatus').text('Chỉnh sửa ghi chú');
        clearPendingImages();
        loadExistingImages($card.data('note-id'));
        window.clearTimeout(saveTimer);
        bootstrap.Modal.getOrCreateInstance(document.getElementById('editorModal')).show();
    }

    function loadExistingImages(noteId) {
        $('#existingNoteImages').empty();
        updateImagesFrameVisibility();
        axios.get(noteUrl('note-show-url-template', noteId))
            .then(function (response) {
                (response.data.note.images || []).forEach(function (image) {
                    const $item = $('<div>', {
                        class: 'note-image-item',
                        'data-image-id': image.id
                    });
                    $('<img>', {
                        src: image.url,
                        alt: 'Ảnh đính kèm ghi chú',
                        class: 'image-preview-trigger',
                        tabindex: 0,
                        role: 'button',
                        'aria-label': 'Mở ảnh kích thước lớn'
                    }).appendTo($item);
                    $('<button>', {
                        type: 'button',
                        class: 'note-image-remove remove-existing-note-image',
                        text: '×',
                        'aria-label': 'Xóa ảnh'
                    }).appendTo($item);
                    $item.appendTo('#existingNoteImages');
                });
                updateImagesFrameVisibility();
            })
            .catch(function (error) {
                console.error('Không thể tải ảnh ghi chú.', error);
                $('#autosaveStatus').text('Không thể tải ảnh đính kèm.');
            });
    }

    $('#noteImages').on('change', function () {
        const files = Array.from(this.files || []);
        const invalidFile = files.find(function (file) {
            return !['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 5 * 1024 * 1024;
        });
        if (invalidFile) {
            window.alert('Chỉ nhận ảnh JPG, PNG, WebP có dung lượng tối đa 5 MB mỗi ảnh.');
            this.value = '';
            return;
        }
        if (pendingImageFiles.length + files.length > 10) {
            window.alert('Mỗi ghi chú chỉ được đính kèm tối đa 10 ảnh.');
            this.value = '';
            return;
        }

        pendingImageFiles = pendingImageFiles.concat(files);
        this.value = '';
        renderPendingImages();
    });

    function renderPendingImages() {
        pendingImageUrls.forEach(function (url) {
            URL.revokeObjectURL(url);
        });
        pendingImageUrls = [];
        const $preview = $('#noteImagePreview').empty();
        pendingImageFiles.forEach(function (file, index) {
            const imageUrl = URL.createObjectURL(file);
            pendingImageUrls.push(imageUrl);
            const $item = $('<div>', {
                class: 'note-image-item',
                'data-file-index': index
            });
            $('<img>', {
                src: imageUrl,
                alt: 'Ảnh xem trước',
                class: 'image-preview-trigger',
                tabindex: 0,
                role: 'button',
                'aria-label': 'Mở ảnh kích thước lớn'
            }).appendTo($item);
            $('<button>', {
                type: 'button',
                class: 'note-image-remove remove-pending-note-image',
                text: '×',
                'aria-label': 'Bỏ ảnh này'
            }).appendTo($item);
            $preview.append($item);
        });
        updateImagesFrameVisibility();
    }

    $('#noteImagePreview').on('click', '.remove-pending-note-image', function () {
        const index = Number($(this).closest('.note-image-item').data('file-index'));
        pendingImageFiles.splice(index, 1);
        renderPendingImages();
    });

    $('#existingNoteImages').on('click', '.remove-existing-note-image', function () {
        const $item = $(this).closest('.note-image-item');
        const url = noteUrl(
            'note-image-delete-url-template',
            editingNote.id,
            $item.data('image-id')
        );
        $(this).prop('disabled', true);
        axios.delete(url)
            .then(function () {
                $item.remove();
                updateImagesFrameVisibility();
            })
            .catch(function (error) {
                console.error('Không thể xóa ảnh ghi chú.', error);
                window.alert(error.response?.data?.message || 'Không thể xóa ảnh.');
                $item.find('.note-image-remove').prop('disabled', false);
            });
    });

    function openImageViewer(imageUrl, trigger) {
        imageViewerTrigger = trigger;
        $('#imageViewerContent').attr('src', imageUrl);
        $('#imageViewer').removeClass('d-none');
        $('#closeImageViewer').trigger('focus');
    }

    function closeImageViewer() {
        $('#imageViewer').addClass('d-none');
        $('#imageViewerContent').attr('src', '');
        if (imageViewerTrigger) {
            imageViewerTrigger.focus();
            imageViewerTrigger = null;
        }
    }

    $('#editorModal').on('click', '.image-preview-trigger', function () {
        openImageViewer(this.src, this);
    });

    $('#editorModal').on('keydown', '.image-preview-trigger', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            openImageViewer(this.src, this);
        }
    });

    $('#closeImageViewer').on('click', closeImageViewer);
    $('#imageViewer').on('click', function (event) {
        if (event.target === this) {
            closeImageViewer();
        }
    });

    $(document).on('keydown', function (event) {
        if (event.key === 'Escape' && !$('#imageViewer').hasClass('d-none')) {
            closeImageViewer();
        }
    });

    function uploadNoteImages(noteId) {
        const files = pendingImageFiles;
        if (!files || files.length === 0) {
            return Promise.resolve();
        }

        const formData = new FormData();
        Array.from(files).forEach(function (file) {
            formData.append('images[]', file);
        });

        return axios.post(noteUrl('note-images-store-url-template', noteId), formData);
    }

    $('.edit-note').on('click', function (event) {
        event.stopPropagation();
        openEditModal($(this).closest('.note-card'));
    });

    $('.note-hover-actions').on('click', function (event) {
        event.stopPropagation();
    });

    $('.note-card').on('click', function () {
        openEditModal($(this));
    });

    $('#confirmDelete').on('click', function () {
        if (cardToDelete) {
            const deleteUrl = cardToDelete.data('delete-url');
            axios.delete(deleteUrl)
                .then(function () {
                    cardToDelete = null;
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal')).hide();
                    window.location.reload();
                })
                .catch(function (error) {
                    console.error('Không thể xóa ghi chú.', error);
                    window.alert(error.response?.data?.message || 'Không thể xóa ghi chú.');
                });
        }
    });

    $('#saveNote').on('click', function () {
        const $button = $(this);
        $button.prop('disabled', true).text('Saving...');
        window.clearTimeout(autosaveTimer);

        const payload = {
            title: $('#editorNoteTitle').val(),
            content: $('#editorNoteContent').val()
        };
        const request = editingNote
            ? axios.put(editingNote.updateUrl, payload)
            : axios.post($('meta[name="note-store-url"]').attr('content'), payload);

        request
            .then(function (response) {
                const noteId = editingNote ? editingNote.id : response.data.note?.id;
                if (!noteId) {
                    throw new Error('Không nhận được ID ghi chú sau khi lưu.');
                }

                if (!editingNote) {
                    editingNote = {
                        id: noteId,
                        card: null,
                        updateUrl: noteUrl('note-update-url-template', noteId)
                    };
                    $('#editorModalTitle').text('Edit Note');
                }

                return uploadNoteImages(noteId).then(function () {
                    justSavedNote = true;
                    window.clearTimeout(saveTimer);
                    pendingImageFiles = [];
                    pendingImageUrls.forEach(function (url) {
                        URL.revokeObjectURL(url);
                    });
                    pendingImageUrls = [];
                    $('#noteImages').val('');
                    $('#editorNoteTitle').val('');
                    $('#editorNoteContent').val('');
                    window.localStorage.removeItem(draftKey);
                    editingNote = null;
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('editorModal')).hide();
                    window.location.reload();
                });
            })
            .catch(function (error) {
                const message = error.response?.data?.message
                    || (error.response?.data?.errors ? Object.values(error.response.data.errors)[0][0] : null)
                    || 'Không thể lưu ghi chú.';
                $('#autosaveStatus').text(message);
            })
            .finally(function () {
                $button.prop('disabled', false).text(editingNote ? 'Save' : 'Add');
            });
    });

    $('.create-note-trigger').on('click', function () {
        editingNote = null;
        $('#editorModalTitle').text('Add New Note');
        $('#saveNote').text('Add');
        $('#existingNoteImages').empty();
        clearPendingImages();
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

    function autosaveNote() {
        const payload = {
            title: $('#editorNoteTitle').val(),
            content: $('#editorNoteContent').val(),
        };

        if (!payload.title.trim() && !payload.content.trim()) {
            return;
        }

        $('#autosaveStatus').text('Đang lưu...');

        const request = editingNote
            ? axios.put(editingNote.updateUrl, payload)
            : axios.post($('meta[name="note-store-url"]').attr('content'), payload);

        request
            .then(function (response) {
                if (!editingNote) {
                    const note = response.data.note;
                    editingNote = {
                        id: note.id,
                        card: null,
                        updateUrl: noteUrl('note-update-url-template', note.id),
                    };
                    $('#editorModalTitle').text('Edit Note');
                    $('#saveNote').text('Save');
                }
                window.localStorage.removeItem(draftKey);
                $('#autosaveStatus').text('Đã lưu');
            })
            .catch(function (error) {
                const message = error.response?.data?.message || 'Không thể lưu ghi chú.';
                $('#autosaveStatus').text(message);
            });
    }

    $('#editorNoteTitle, #editorNoteContent').on('input', function () {
        $('#autosaveStatus').text('Đang lưu...');
        window.clearTimeout(saveTimer);
        saveTimer = window.setTimeout(saveDraft, 400);
        window.clearTimeout(autosaveTimer);
        autosaveTimer = window.setTimeout(autosaveNote, 1000);
    });

    $('#editorModal').on('hidden.bs.modal', function () {
        window.clearTimeout(saveTimer);
        if (justSavedNote) {
            window.localStorage.removeItem(draftKey);
            $('#editorNoteTitle').val('');
            $('#editorNoteContent').val('');
            justSavedNote = false;
            return;
        }
        if (editingNote) {
            editingNote = null;
            $('#editorModalTitle').text('Add New Note');
            $('#saveNote').text('Add');
            $('#editorNoteTitle').val('');
            $('#editorNoteContent').val('');
            window.localStorage.removeItem(draftKey);
            return;
        }
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
