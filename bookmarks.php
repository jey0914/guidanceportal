<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wellness Library Bookmarks</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="/_sdk/data_sdk.js"></script>
  <script src="/_sdk/element_sdk.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
  <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .bookmark-card {
            transition: all 0.3s ease;
        }
        
        .bookmark-card:hover {
            transform: translateY(-4px);
        }
        
        .star {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .star:hover {
            transform: scale(1.2);
        }
        
        .modal-backdrop {
            backdrop-filter: blur(4px);
        }
        
        .toast {
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .category-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
    </style>

 </head>
 <body>
  <div id="app" style="width: 100%; min-height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"><!-- Navigation -->
   <nav style="background: rgba(255, 255, 255, 0.95); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); padding: 1rem 2rem;">
    <div style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between;">
     <div style="display: flex; align-items: center; gap: 1rem;"><i class="fas fa-bookmark" style="font-size: 1.5rem; color: #667eea;"></i>
      <h1 id="pageTitle" style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1f2937;">My Bookmarks</h1>
     </div>
     <div class="nav-links"><a href="dashboard.php" class="nav-link" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; color: #4b5563; text-decoration: none; border-radius: 0.5rem; transition: all 0.2s;"> <i class="fas fa-home"></i> Dashboard </a>
     </div>
    </div>
   </nav><!-- Main Content -->
   <main style="max-width: 1200px; margin: 0 auto; padding: 2rem;"><!-- Filters and Add Button -->
    <div style="background: white; border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
     <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;">
      <div style="display: flex; gap: 1rem; flex-wrap: wrap; flex: 1;"><select id="categoryFilter" style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.875rem; color: #374151; cursor: pointer; outline: none; min-width: 150px;"> <option value="all">All Categories</option> <option value="mindfulness">Mindfulness</option> <option value="nutrition">Nutrition</option> <option value="fitness">Fitness</option> <option value="mental-health">Mental Health</option> <option value="sleep">Sleep</option> <option value="other">Other</option> </select> <input type="text" id="searchInput" placeholder="Search bookmarks..." style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.875rem; outline: none; flex: 1; min-width: 200px;">
      </div><button id="addBookmarkBtn" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; border: none; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s;"> <i class="fas fa-plus"></i> <span id="addButtonText">Add Bookmark</span> </button>
     </div>
    </div><!-- Bookmarks Grid -->
    <div id="bookmarksContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;"><!-- Bookmarks will be rendered here -->
    </div><!-- Empty State -->
    <div id="emptyState" style="display: none; text-align: center; padding: 4rem 2rem; background: white; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"><i class="fas fa-bookmark" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
     <h3 style="font-size: 1.5rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">No bookmarks yet</h3>
     <p id="emptyMessage" style="color: #6b7280; margin-bottom: 1.5rem;">Start building your wellness library by adding your first bookmark!</p><button onclick="document.getElementById('addBookmarkBtn').click()" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; border: none; font-weight: 600; cursor: pointer;"> Add Your First Bookmark </button>
    </div>
   </main><!-- Add/Edit Modal -->
   <div id="bookmarkModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 50;">
    <div class="modal-backdrop" onclick="closeModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5);"></div>
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 1rem; padding: 2rem; width: 90%; max-width: 500px; max-height: 90%; overflow-y: auto; box-shadow: 0 20px 25px rgba(0, 0, 0, 0.3);">
     <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
      <h2 id="modalTitle" style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1f2937;">Add Bookmark</h2><button onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; color: #9ca3af; cursor: pointer;"> <i class="fas fa-times"></i> </button>
     </div>
     <form id="bookmarkForm" onsubmit="return false;">
      <div style="margin-bottom: 1rem;"><label for="bookmarkTitle" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Title</label> <input type="text" id="bookmarkTitle" required style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; outline: none; font-size: 1rem;">
      </div>
      <div style="margin-bottom: 1rem;"><label for="bookmarkAuthor" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Author/Source</label> <input type="text" id="bookmarkAuthor" required style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; outline: none; font-size: 1rem;">
      </div>
      <div style="margin-bottom: 1rem;"><label for="bookmarkCategory" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Category</label> <select id="bookmarkCategory" required style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; outline: none; font-size: 1rem; cursor: pointer;"> <option value="">Select a category</option> <option value="mindfulness">Mindfulness</option> <option value="nutrition">Nutrition</option> <option value="fitness">Fitness</option> <option value="mental-health">Mental Health</option> <option value="sleep">Sleep</option> <option value="other">Other</option> </select>
      </div>
      <div style="margin-bottom: 1rem;"><label for="bookmarkRating" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Rating</label>
       <div id="ratingInput" style="display: flex; gap: 0.5rem; font-size: 1.5rem;"><i class="fas fa-star star" data-rating="1" style="color: #d1d5db;"></i> <i class="fas fa-star star" data-rating="2" style="color: #d1d5db;"></i> <i class="fas fa-star star" data-rating="3" style="color: #d1d5db;"></i> <i class="fas fa-star star" data-rating="4" style="color: #d1d5db;"></i> <i class="fas fa-star star" data-rating="5" style="color: #d1d5db;"></i>
       </div>
      </div>
      <div style="margin-bottom: 1.5rem;"><label for="bookmarkNotes" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Notes</label> <textarea id="bookmarkNotes" rows="4" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; outline: none; font-size: 1rem; resize: vertical;"></textarea>
      </div>
      <div style="display: flex; gap: 1rem; justify-content: flex-end;"><button type="button" onclick="closeModal()" style="padding: 0.75rem 1.5rem; border: 2px solid #e5e7eb; background: white; color: #374151; border-radius: 0.5rem; font-weight: 600; cursor: pointer;"> Cancel </button> <button type="submit" id="saveBookmarkBtn" style="padding: 0.75rem 1.5rem; background: #667eea; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;"> Save Bookmark </button>
      </div>
     </form>
    </div>
   </div><!-- Toast Notification -->
   <div id="toast" style="display: none; position: fixed; top: 2rem; right: 2rem; background: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2); z-index: 60; max-width: 300px;">
    <div style="display: flex; align-items: center; gap: 0.75rem;"><i id="toastIcon" class="fas fa-check-circle" style="font-size: 1.25rem; color: #10b981;"></i>
     <p id="toastMessage" style="margin: 0; color: #374151; font-weight: 500;"></p>
    </div>
   </div>
  </div>
  <script>
        const defaultConfig = {
            page_title: "My Bookmarks",
            empty_message: "Start building your wellness library by adding your first bookmark!",
            add_button_text: "Add Bookmark",
            background_color: "#667eea",
            card_color: "#ffffff",
            text_color: "#1f2937",
            primary_action_color: "#667eea",
            secondary_action_color: "#ef4444",
            font_family: "Inter",
            font_size: 16
        };

        let bookmarksData = [];
        let currentFilter = 'all';
        let searchQuery = '';
        let editingBookmarkId = null;
        let currentRating = 0;

        const dataHandler = {
            onDataChanged(data) {
                bookmarksData = data;
                renderBookmarks();
            }
        };

        async function onConfigChange(config) {
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;
            const backgroundColor = config.background_color || defaultConfig.background_color;
            const cardColor = config.card_color || defaultConfig.card_color;
            const textColor = config.text_color || defaultConfig.text_color;
            const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;
            const secondaryColor = config.secondary_action_color || defaultConfig.secondary_action_color;

            document.getElementById('app').style.background = `linear-gradient(135deg, ${backgroundColor} 0%, ${backgroundColor}dd 100%)`;
            document.getElementById('pageTitle').textContent = config.page_title || defaultConfig.page_title;
            document.getElementById('emptyMessage').textContent = config.empty_message || defaultConfig.empty_message;
            document.getElementById('addButtonText').textContent = config.add_button_text || defaultConfig.add_button_text;

            document.body.style.fontSize = `${baseFontSize}px`;
            document.getElementById('pageTitle').style.fontSize = `${baseFontSize * 1.5}px`;
            document.getElementById('pageTitle').style.color = textColor;
            document.getElementById('pageTitle').style.fontFamily = `${customFont}, sans-serif`;

            const addBtn = document.getElementById('addBookmarkBtn');
            addBtn.style.background = primaryColor;
            addBtn.style.fontFamily = `${customFont}, sans-serif`;

            renderBookmarks();
        }

        async function initApp() {
            const sdkResult = await window.dataSdk.init(dataHandler);
            if (!sdkResult.isOk) {
                showToast('Failed to initialize app', 'error');
                return;
            }

            if (window.elementSdk) {
                window.elementSdk.init({
                    defaultConfig,
                    onConfigChange,
                    mapToCapabilities: (config) => ({
                        recolorables: [
                            {
                                get: () => config.background_color || defaultConfig.background_color,
                                set: (value) => {
                                    config.background_color = value;
                                    window.elementSdk.setConfig({ background_color: value });
                                }
                            },
                            {
                                get: () => config.card_color || defaultConfig.card_color,
                                set: (value) => {
                                    config.card_color = value;
                                    window.elementSdk.setConfig({ card_color: value });
                                }
                            },
                            {
                                get: () => config.text_color || defaultConfig.text_color,
                                set: (value) => {
                                    config.text_color = value;
                                    window.elementSdk.setConfig({ text_color: value });
                                }
                            },
                            {
                                get: () => config.primary_action_color || defaultConfig.primary_action_color,
                                set: (value) => {
                                    config.primary_action_color = value;
                                    window.elementSdk.setConfig({ primary_action_color: value });
                                }
                            },
                            {
                                get: () => config.secondary_action_color || defaultConfig.secondary_action_color,
                                set: (value) => {
                                    config.secondary_action_color = value;
                                    window.elementSdk.setConfig({ secondary_action_color: value });
                                }
                            }
                        ],
                        borderables: [],
                        fontEditable: {
                            get: () => config.font_family || defaultConfig.font_family,
                            set: (value) => {
                                config.font_family = value;
                                window.elementSdk.setConfig({ font_family: value });
                            }
                        },
                        fontSizeable: {
                            get: () => config.font_size || defaultConfig.font_size,
                            set: (value) => {
                                config.font_size = value;
                                window.elementSdk.setConfig({ font_size: value });
                            }
                        }
                    }),
                    mapToEditPanelValues: (config) => new Map([
                        ["page_title", config.page_title || defaultConfig.page_title],
                        ["empty_message", config.empty_message || defaultConfig.empty_message],
                        ["add_button_text", config.add_button_text || defaultConfig.add_button_text]
                    ])
                });
            }

            setupEventListeners();
        }

        function setupEventListeners() {
            document.getElementById('addBookmarkBtn').addEventListener('click', () => {
                editingBookmarkId = null;
                currentRating = 0;
                document.getElementById('modalTitle').textContent = 'Add Bookmark';
                document.getElementById('bookmarkForm').reset();
                updateRatingStars(0);
                document.getElementById('bookmarkModal').style.display = 'block';
            });

            document.getElementById('categoryFilter').addEventListener('change', (e) => {
                currentFilter = e.target.value;
                renderBookmarks();
            });

            document.getElementById('searchInput').addEventListener('input', (e) => {
                searchQuery = e.target.value.toLowerCase();
                renderBookmarks();
            });

            document.getElementById('bookmarkForm').addEventListener('submit', handleSaveBookmark);

            document.querySelectorAll('#ratingInput .star').forEach(star => {
                star.addEventListener('click', (e) => {
                    currentRating = parseInt(e.target.dataset.rating);
                    updateRatingStars(currentRating);
                });
            });
        }

        function updateRatingStars(rating) {
            document.querySelectorAll('#ratingInput .star').forEach(star => {
                const starRating = parseInt(star.dataset.rating);
                star.style.color = starRating <= rating ? '#fbbf24' : '#d1d5db';
            });
        }

        async function handleSaveBookmark(e) {
            e.preventDefault();

            const title = document.getElementById('bookmarkTitle').value;
            const author = document.getElementById('bookmarkAuthor').value;
            const category = document.getElementById('bookmarkCategory').value;
            const notes = document.getElementById('bookmarkNotes').value;

            if (bookmarksData.length >= 999 && !editingBookmarkId) {
                showToast('Maximum limit of 999 bookmarks reached', 'error');
                return;
            }

            const saveBtn = document.getElementById('saveBookmarkBtn');
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';

            const bookmarkData = {
                id: editingBookmarkId || Date.now().toString(),
                title,
                author,
                category,
                notes,
                rating: currentRating,
                dateAdded: new Date().toISOString()
            };

            let result;
            if (editingBookmarkId) {
                const existingBookmark = bookmarksData.find(b => b.id === editingBookmarkId);
                if (existingBookmark) {
                    result = await window.dataSdk.update({ ...existingBookmark, ...bookmarkData });
                }
            } else {
                result = await window.dataSdk.create(bookmarkData);
            }

            saveBtn.disabled = false;
            saveBtn.textContent = 'Save Bookmark';

            if (result.isOk) {
                showToast(editingBookmarkId ? 'Bookmark updated successfully!' : 'Bookmark added successfully!', 'success');
                closeModal();
            } else {
                showToast('Failed to save bookmark', 'error');
            }
        }

        function renderBookmarks() {
            const container = document.getElementById('bookmarksContainer');
            const emptyState = document.getElementById('emptyState');
            const config = window.elementSdk ? window.elementSdk.config : defaultConfig;
            const cardColor = config.card_color || defaultConfig.card_color;
            const textColor = config.text_color || defaultConfig.text_color;
            const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;
            const secondaryColor = config.secondary_action_color || defaultConfig.secondary_action_color;
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontSize = config.font_size || defaultConfig.font_size;

            let filteredBookmarks = bookmarksData.filter(bookmark => {
                const matchesFilter = currentFilter === 'all' || bookmark.category === currentFilter;
                const matchesSearch = searchQuery === '' || 
                    bookmark.title.toLowerCase().includes(searchQuery) ||
                    bookmark.author.toLowerCase().includes(searchQuery) ||
                    bookmark.notes.toLowerCase().includes(searchQuery);
                return matchesFilter && matchesSearch;
            });

            if (filteredBookmarks.length === 0) {
                container.style.display = 'none';
                emptyState.style.display = 'block';
            } else {
                container.style.display = 'grid';
                emptyState.style.display = 'none';

                container.innerHTML = filteredBookmarks.map(bookmark => {
                    const categoryColors = {
                        'mindfulness': { bg: '#dbeafe', text: '#1e40af' },
                        'nutrition': { bg: '#dcfce7', text: '#166534' },
                        'fitness': { bg: '#fed7aa', text: '#9a3412' },
                        'mental-health': { bg: '#fae8ff', text: '#86198f' },
                        'sleep': { bg: '#e0e7ff', text: '#3730a3' },
                        'other': { bg: '#f3f4f6', text: '#374151' }
                    };
                    const categoryColor = categoryColors[bookmark.category] || categoryColors['other'];

                    const stars = Array.from({ length: 5 }, (_, i) => 
                        `<i class="fas fa-star" style="color: ${i < bookmark.rating ? '#fbbf24' : '#d1d5db'}; font-size: ${baseFontSize * 0.875}px;"></i>`
                    ).join('');

                    return `
                        <div class="bookmark-card" style="background: ${cardColor}; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-family: ${customFont}, sans-serif;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                <div style="flex: 1;">
                                    <h3 style="margin: 0 0 0.5rem 0; font-size: ${baseFontSize * 1.125}px; font-weight: 600; color: ${textColor};">${bookmark.title}</h3>
                                    <p style="margin: 0; font-size: ${baseFontSize * 0.875}px; color: #6b7280;">by ${bookmark.author}</p>
                                </div>
                                <span class="category-badge" style="background: ${categoryColor.bg}; color: ${categoryColor.text}; font-size: ${baseFontSize * 0.75}px; font-family: ${customFont}, sans-serif;">
                                    ${bookmark.category.replace('-', ' ')}
                                </span>
                            </div>
                            
                            <div style="display: flex; gap: 0.25rem; margin-bottom: 1rem;">
                                ${stars}
                            </div>
                            
                            ${bookmark.notes ? `<p style="margin: 0 0 1rem 0; font-size: ${baseFontSize * 0.875}px; color: #6b7280; line-height: 1.5;">${bookmark.notes}</p>` : ''}
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                                <span style="font-size: ${baseFontSize * 0.75}px; color: #9ca3af;">${new Date(bookmark.dateAdded).toLocaleDateString()}</span>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button onclick="editBookmark('${bookmark.id}')" style="padding: 0.5rem 1rem; background: ${primaryColor}; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-size: ${baseFontSize * 0.875}px; font-family: ${customFont}, sans-serif;">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button onclick="deleteBookmark('${bookmark.id}')" style="padding: 0.5rem 1rem; background: ${secondaryColor}; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-size: ${baseFontSize * 0.875}px; font-family: ${customFont}, sans-serif;">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }
        }

        async function editBookmark(id) {
            const bookmark = bookmarksData.find(b => b.id === id);
            if (!bookmark) return;

            editingBookmarkId = id;
            currentRating = bookmark.rating;

            document.getElementById('modalTitle').textContent = 'Edit Bookmark';
            document.getElementById('bookmarkTitle').value = bookmark.title;
            document.getElementById('bookmarkAuthor').value = bookmark.author;
            document.getElementById('bookmarkCategory').value = bookmark.category;
            document.getElementById('bookmarkNotes').value = bookmark.notes;
            updateRatingStars(bookmark.rating);

            document.getElementById('bookmarkModal').style.display = 'block';
        }

        async function deleteBookmark(id) {
            const bookmark = bookmarksData.find(b => b.id === id);
            if (!bookmark) return;

            const result = await window.dataSdk.delete(bookmark);

            if (result.isOk) {
                showToast('Bookmark deleted successfully!', 'success');
            } else {
                showToast('Failed to delete bookmark', 'error');
            }
        }

        function closeModal() {
            document.getElementById('bookmarkModal').style.display = 'none';
            document.getElementById('bookmarkForm').reset();
            editingBookmarkId = null;
            currentRating = 0;
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toastIcon');
            const messageEl = document.getElementById('toastMessage');

            icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            icon.style.color = type === 'success' ? '#10b981' : '#ef4444';
            messageEl.textContent = message;

            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initApp);
        } else {
            initApp();
        }
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a839b2e45080dcb',t:'MTc2NDc3MDU5My4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>