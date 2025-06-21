<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu Management - InkluSwift</title>
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('Assets/logo hd.png') }}" alt="InkluSwift Logo">
            <h1>InkluSwift</h1>
        </div>

        <div class="auth-buttons">
            <a href="{{ route('landing') }}" class="btn btn-primary">
                <img src="{{ asset('Assets/logout.png') }}" alt="Logout Icon">
                Logout
            </a>
        </div>
    </header>
    
    <div class="container">
        <button class="back-btn" onclick="window.location.href='{{ route('admin') }}'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Dashboard
        </button>
        
        <h1 class="page-title">Menu Management</h1>
        
        <div class="search-filter">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search by ID, name, or description">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            
            <div class="filter-dropdown">
                <span>Filter by category</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
        </div>
        
        <div class="menu-table">
            <div class="menu-header">
                <div>MENU ID</div>
                <div>IMAGE</div>
                <div>NAME</div>
                <div>PRICE</div>
                <div>STOCK</div>
                <div>DISCOUNT</div>
                <div>CATEGORY</div>
                <div>STORAGE</div>
                <div>ACTION</div>
            </div>
            
            <div class="menu-form" id="menuForm">
                <input type="hidden" id="menuId">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>MENU NAME</label>
                            <input type="text" class="form-control" id="menuName" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>PRICE</label>
                            <input type="number" class="form-control" id="menuPrice" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>STOCK</label>
                            <input type="number" class="form-control" id="menuStock" min="0" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>DISCOUNT (%)</label>
                            <input type="number" class="form-control" id="menuDiscount" min="0" max="100" step="0.01">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>CATEGORY</label>
                            <input type="text" class="form-control" id="menuCategory" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>STORAGE</label>
                            <input type="text" class="form-control" id="menuStorage">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>DESCRIPTION</label>
                    <textarea class="form-control" id="menuDescription" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>INGREDIENTS</label>
                    <textarea class="form-control" id="menuIngredients" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>MENU IMAGE</label>
                    <div class="image-upload">
                        <div class="image-preview" id="imagePreview">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            <img id="previewImage" alt="Preview">
                            <div class="remove-btn" id="removeImageBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </div>
                        </div>
                        <div>
                            <input type="file" id="imageUpload" accept="image/*" style="display: none;">
                            <button class="upload-btn" id="uploadBtn">UPLOAD</button>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="save-btn" id="saveBtn">SAVE</button>
                <button type="button" class="save-btn" id="cancelBtn" style="background-color: #777; margin-top: 10px;">CANCEL</button>
            </div>
            
            <div id="menuItemsContainer">
                <!-- Menu items will be dynamically inserted here -->
                <div class="no-data">No menus found. Click "Add New Menu" to create one.</div>
            </div>
        </div>
        
        <div class="pagination">
            <div class="showing-info">
                Showing
                <select id="itemsPerPage">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                of <span id="totalItems">0</span>
            </div>
            
            <div class="pagination-buttons" id="paginationButtons">
                <!-- Pagination buttons will be dynamically inserted here -->
            </div>
        </div>
        
        <button class="add-menu-btn" id="addMenuBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Add New Menu
        </button>
    </div>
    
    <footer>
        All rights Reserved © 2025, InkluSwift
    </footer>

    <script src="{{ asset('js/menu.js') }}"></script>
</body>
</html>