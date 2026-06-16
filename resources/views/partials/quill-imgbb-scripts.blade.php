<!-- Quill & ImgBB Global Scripts -->
<script>
    // Trigger programmatic click on ImgBB upload widget button
    function triggerImgBBWidget(editorId, containerId) {
        const container = document.getElementById(containerId);
        let widgetBtn = null;
        if (container) {
            let next = container.nextElementSibling;
            while (next) {
                if (next.classList.contains('imgbb-container')) {
                    widgetBtn = next.querySelector('button');
                    break;
                }
                const btn = next.querySelector('button[data-imgbb-trigger]');
                if (btn) {
                    widgetBtn = btn;
                    break;
                }
                next = next.nextElementSibling;
            }
        }
        if (!widgetBtn) {
            const quillContainer = document.getElementById(editorId);
            if (quillContainer) {
                let targetId = quillContainer.getAttribute('data-imgbb-target');
                if (!targetId) {
                    const qlEditor = quillContainer.querySelector('.ql-editor');
                    if (qlEditor) {
                        targetId = qlEditor.getAttribute('data-imgbb-target');
                    }
                }
                if (targetId) {
                    widgetBtn = document.querySelector(`[data-imgbb-id="${targetId}"]`);
                }
            }
        }
        if (!widgetBtn) {
            widgetBtn = document.querySelector(`[data-sibling-selector="#${containerId}"]`) || document.querySelector('button[data-imgbb-trigger]');
        }
        if (widgetBtn) {
            widgetBtn.click();
        } else {
            window.open('https://imgbb.com/upload', '_blank');
        }
    }

    // Global message interceptor for ImgBB manual uploads
    window.addEventListener("message", function(event) {
        if (event.data && typeof event.data === 'object' && event.data.id && event.data.message) {
            const msg = event.data.message;
            const imgRegex = /\[img\](.*?)\[\/img\]|<img[^>]+src="([^">]+)"|https?:\/\/[^\s]+(?:\.png|\.jpg|\.jpeg|\.gif)/gi;
            let match;
            let foundUrls = [];
            while ((match = imgRegex.exec(msg)) !== null) {
                const url = match[1] || match[2] || match[0];
                if (url && !foundUrls.includes(url)) {
                    foundUrls.push(url);
                }
            }
            
            if (foundUrls.length > 0) {
                const id = event.data.id;
                let targetEditor = null;
                
                const mainEditor = document.getElementById('quill-editor');
                const mainInput = document.getElementById('content-input');
                if ((mainEditor && (mainEditor.getAttribute('data-imgbb-target') === id || mainEditor.querySelector('.ql-editor')?.getAttribute('data-imgbb-target') === id)) ||
                    (mainInput && mainInput.getAttribute('data-imgbb-target') === id)) {
                    targetEditor = typeof quill !== 'undefined' ? quill : null;
                }
                
                const replyEditor = document.getElementById('reply-quill-editor');
                const replyInput = document.getElementById('reply-content-input');
                if ((replyEditor && (replyEditor.getAttribute('data-imgbb-target') === id || replyEditor.querySelector('.ql-editor')?.getAttribute('data-imgbb-target') === id)) ||
                    (replyInput && replyInput.getAttribute('data-imgbb-target') === id)) {
                    targetEditor = typeof replyQuill !== 'undefined' ? replyQuill : null;
                }
                
                const editEditor = document.getElementById('edit-post-quill-editor');
                const editInput = document.getElementById('edit-post-content-input');
                if ((editEditor && (editEditor.getAttribute('data-imgbb-target') === id || editEditor.querySelector('.ql-editor')?.getAttribute('data-imgbb-target') === id)) ||
                    (editInput && editInput.getAttribute('data-imgbb-target') === id)) {
                    targetEditor = typeof editPostQuill !== 'undefined' ? editPostQuill : null;
                }
                
                if (!targetEditor) {
                    if (typeof quill !== 'undefined') targetEditor = quill;
                    else if (typeof replyQuill !== 'undefined') targetEditor = replyQuill;
                    else if (typeof editPostQuill !== 'undefined') targetEditor = editPostQuill;
                }
                
                if (targetEditor) {
                    foundUrls.forEach(url => {
                        const range = targetEditor.getSelection(true);
                        targetEditor.insertEmbed(range.index, 'image', url);
                        targetEditor.setSelection(range.index + 1);
                    });
                    
                    // Clean up any raw BBCode appended to the editor's innerHTML
                    setTimeout(() => {
                        const editorEl = targetEditor.root;
                        if (editorEl) {
                            let html = editorEl.innerHTML;
                            if (html.includes('[img]') || html.includes('[/img]')) {
                                html = html.replace(/\[url=.*?\]\[img\].*?\[\/img\]\[\/url\]|\[img\].*?\[\/img\]/gi, '');
                                editorEl.innerHTML = html;
                            }
                        }
                    }, 50);
                }
            }
        }
    });

    /**
     * Generic handler to insert an image into a Quill instance.
     * Prompts for file upload or direct URL insertion.
     */
    function handleQuillImageInsertion(quillInstance, containerId, editorId, uploadRoute, csrfToken) {
        Swal.fire({
            title: '🖼️ Insert Image',
            text: 'Select how you want to add an image to your post:',
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: '📤 Upload File',
            denyButtonText: '🔗 Paste Image URL',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#0f172a',
            denyButtonColor: '#4f46e5',
            cancelButtonColor: '#e11d48',
        }).then((result) => {
            if (result.isConfirmed) {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/jpeg, image/png, image/jpg, image/gif');
                input.click();

                input.onchange = () => {
                    const file = input.files[0];
                    if (/^image\//.test(file.type)) {
                        uploadQuillImageToImgBB(file, quillInstance, containerId, editorId, uploadRoute, csrfToken);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File',
                            text: 'Only image files (JPEG, PNG, JPG, GIF) are allowed.',
                            confirmButtonColor: '#0f172a'
                        });
                    }
                };
            } else if (result.isDenied) {
                Swal.fire({
                    title: '🔗 Paste Image URL',
                    input: 'url',
                    inputPlaceholder: 'https://example.com/image.jpg',
                    showCancelButton: true,
                    confirmButtonText: 'Insert Image',
                    confirmButtonColor: '#0f172a',
                    cancelButtonColor: '#e11d48',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'You need to write something!';
                        }
                        if (!value.match(/^https?:\/\/.+/)) {
                            return 'Please enter a valid URL!';
                        }
                    }
                }).then((urlResult) => {
                    if (urlResult.isConfirmed && urlResult.value) {
                        const range = quillInstance.getSelection(true);
                        quillInstance.insertEmbed(range.index, 'image', urlResult.value.trim());
                        quillInstance.setSelection(range.index + 1);
                    }
                });
            }
        });
    }

    /**
     * Generic background uploader to upload image to ImgBB and insert into Quill.
     */
    function uploadQuillImageToImgBB(file, quillInstance, containerId, editorId, uploadRoute, csrfToken) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', csrfToken);

        const range = quillInstance.getSelection(true);
        quillInstance.insertEmbed(range.index, 'image', 'https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif'); // Temp spinner

        fetch(uploadRoute, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Upload failed');
            }
            return response.json();
        })
        .then(data => {
            quillInstance.deleteText(range.index, 1);
            if (data.url) {
                quillInstance.insertEmbed(range.index, 'image', data.url);
                quillInstance.setSelection(range.index + 1);
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Upload Failed',
                    html: `
                        <div class="text-center text-sm">
                            <p class="mb-4 text-slate-600 dark:text-slate-400">Background upload failed. Please use the manual upload widget to upload your image.</p>
                            <button id="swal-manual-upload-btn" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-805 transition-all text-xs">
                                Open Upload Widget
                            </button>
                        </div>
                    `,
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    cancelButtonColor: '#e11d48',
                    didOpen: () => {
                        const swalBtn = document.getElementById('swal-manual-upload-btn');
                        if (swalBtn) {
                            swalBtn.addEventListener('click', () => {
                                triggerImgBBWidget(editorId, containerId);
                                Swal.close();
                            });
                        }
                    }
                });
            }
        })
        .catch(error => {
            quillInstance.deleteText(range.index, 1);
            console.error('Quill Image Upload Error:', error);
            Swal.fire({
                icon: 'warning',
                title: 'Upload Failed',
                html: `
                    <div class="text-center text-sm">
                        <p class="mb-4 text-slate-600 dark:text-slate-400">An error occurred during upload. Please use the manual upload widget to upload your image.</p>
                        <button id="swal-manual-upload-btn" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-805 transition-all text-xs">
                            Open Upload Widget
                        </button>
                    </div>
                `,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                cancelButtonColor: '#e11d48',
                didOpen: () => {
                    const swalBtn = document.getElementById('swal-manual-upload-btn');
                    if (swalBtn) {
                        swalBtn.addEventListener('click', () => {
                            triggerImgBBWidget(editorId, containerId);
                            Swal.close();
                        });
                    }
                }
            });
        });
    }
</script>
