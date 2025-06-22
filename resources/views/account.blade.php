<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Account Management - InkluSwift</title>
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Script aksesibilitas -->
    <script src="{{ asset('js/accessibility.js') }}"></script>
</head>
<body>
    <header>
        <div class="logo">
            <a href="{{ route('admin') }}" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <img src="{{asset('Assets/logo hd.png')}}" alt="InkluSwift Logo">
                <h1>InkluSwift</h1>
            </a>
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
        
        <h1 class="page-title">Account Management</h1>
        
        <div class="search-filter">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search by ID, name, or email">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            
            <div class="filter-dropdown">
                <span>Filter by role</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
        </div>
        
        <div class="account-table">
            <div class="account-header">
                <div>USER ID</div>
                <div>NAME</div>
                <div>EMAIL</div>
                <div>PHONE</div>
                <div>ROLE</div>
                <div>STATUS</div>
                <div>JOIN DATE</div>
                <div>ACTION</div>
            </div>
            
            <div class="account-form" id="accountForm">
                <input type="hidden" id="userId">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>FULL NAME</label>
                            <input type="text" class="form-control" id="userName" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>EMAIL</label>
                            <input type="email" class="form-control" id="userEmail" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>PHONE</label>
                            <input type="tel" class="form-control" id="userPhone">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>PASSWORD</label>
                            <input type="password" class="form-control" id="userPassword" placeholder="Leave blank to keep current">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>CONFIRM PASSWORD</label>
                            <input type="password" class="form-control" id="userConfirmPassword">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>ROLE</label>
                            <select class="form-control" id="userRole" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="customer">Customer</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>STATUS</label>
                            <select class="form-control" id="userStatus" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>ADDRESS</label>
                    <textarea class="form-control" id="userAddress" rows="2"></textarea>
                </div>
                
                <div class="form-group">
                    <label>PROFILE PICTURE</label>
                    <div class="image-upload">
                        <div class="image-preview" id="imagePreview">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
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
            
            <div id="accountItemsContainer">
                <!-- Account items will be dynamically inserted here -->
                <div class="no-data">No accounts found. Click "Add New Account" to create one.</div>
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
        
        <button class="add-account-btn" id="addAccountBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Add New Account
        </button>
    </div>
    
    <footer>
        All rights Reserved © 2025, InkluSwift
    </footer>

    <script src="{{ asset('js/account.js') }}"></script>
</body>
</html>