

<div class="header bg-dark">
        <div id="menu-icon">&#9776;</div>
    </div>

    <div class="sidebar">
        <button><h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3></button>
        <button onclick="window.location.href='../customer/customer.php'"><i class="fas fa-list-ul"></i> Customer List</button>
        <button onclick="window.location.href='../customer/add_customer.php'"><i class="fas fa-user-plus"></i> Add Customer</button>
        <button type="button" data-toggle="popover" title="Customer Management" data-content="Here you can create, update, delete and view customer profiles." data-placement="bottom"><i class="fas fa-question"></i> Help</button>
        <div class="fixed-bottom">
            <button class="btn m-2 p-2" onclick="window.location.href='../main.php'"><i class="fas fa-arrow-alt-circle-left"></i> Back</button>
        </div>
    </div>

	<script>
        document.getElementById('menu-icon').addEventListener('click', function () {
            var ul = document.querySelector('.sidebar ');
            ul.style.display = (ul.style.display === 'none' || ul.style.display === '') ? 'block' : 'none';
        });
    </script>
