<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .sidebar ul li {
            position: relative;
            color:white;
        }
        .dropdown-menu {
            display: none;
            position: relative;
            left: 0;
            right: 0;
            color:white;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }
        .dropdown-menu li a {
            color: black;
        }
        .dropdown-menu.visible {
            display: block;
        }
         /* If it's not turning white, force it. */
        .dropdown-menu li a {
        color: white !important; 
    }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white p-4">
    <div class="flex justify-center">
    </div>
    <?php

   
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        $stmt = $conn->prepare("SELECT * FROM `tbl_user` WHERE tbl_user_id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $username = $user['username'];
            echo "
            <h2 class='text-2xl font-semibold text-center' id='username-$userId'>$username
            <span id='sidebar-toggle' class='text-white text-3xl cursor-pointer'>&gt;</span>
            </h2>";
        } else {
            echo "<p class='text-red-500'>User not found.</p>";
        }
    } else {
        echo "<p class='text-red-500'>You are not logged in.</p>";
    }
    ?>

    <ul class="mt-6">
        <li>
            <a href="../public/user-page.php" class="block px-4 py-2 mt-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-home text-white"></i> Home
            </a>
        </li>
        <li>
            <a href="../public/user-profile.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
                <i class="fas fa-user"></i> Profile
            </a>
        </li>
        <li>
            <a href="../public/user-post.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
                <i class="fas fa-edit"></i> Post
            </a>
        </li>
        <li>
            <a href="../public/user-message.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
                <i class="fas fa-envelope"></i> Messages
            </a>
        </li>
        <li>
            <a href="../public/user-notification.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
                <i class="fas fa-envelope"></i> Notification
            </a>
        </li>
        <li class="relative">
            <a href="#" onclick="toggleDropdown(event, 'settings-dropdown')" class="block px-4 py-2 mt-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-cog"> </i>  Settings
                <i class="fas fa-chevron-down ml-auto"></i>
            </a>
            <ul id="settings-dropdown" class="dropdown-menu">
           <li>
    <a href="#" onclick="editProfilePopup()" class="block px-4 py-2 text-white flex items-center">
        <i class="fas fa-user-edit mr-2"></i>
        Edit Profile
    </a>
</li>

                <!-- Add more settings options here if needed -->
            </ul>
        </li>
        <li>
            <a href="../public/user-community.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
                <i class="fas fa-users"></i> Community
            </a>
        </li>
        <li>
            <a href="../public/user-logout.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</div>

<script>
    document.getElementById('sidebar-toggle').addEventListener('click', function () {
        var sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('sidebar-hidden');
        sidebar.classList.toggle('sidebar-visible');
        var content = document.querySelector('.main-content');
        content.classList.toggle('ml-64');
        this.textContent = this.textContent === '>' ? '<' : '>';
    });

    function toggleDropdown(event, dropdownId) {
        event.preventDefault();
        var dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle('visible');
    }

    function editProfilePopup() {
        Swal.fire({
            title: 'Edit Profile',
            html: `
                <form id="edit-profile-form" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="profile_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                        <input type="file" name="profile_image" id="profile_image" class="mt-1 block w-full">
                    </div>
                    <div class="mb-4">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save Changes</button>
                </form>
            `,
            showConfirmButton: false
        });

        document.getElementById('edit-profile-form').addEventListener('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);

            fetch('../public/utils/update-profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('Failed')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Profile updated successfully!'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the profile.'
                });
            });
        });
    }
</script>
</body>
</html>
