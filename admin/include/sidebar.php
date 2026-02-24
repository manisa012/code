<div class="span3">
    <div class="sidebar">
        <ul class="widget widget-menu unstyled">
            <li>
                <a class="collapsed" data-toggle="collapse" href="#togglePages">
                    <i class="menu-icon icon-cog"></i>
                    <i class="icon-chevron-down pull-right"></i><i class="icon-chevron-up pull-right"></i>
                    Order Management
                </a>
                <ul id="togglePages" class="collapse unstyled">
                    <li>
                        <a href="todays-orders.php">
                            <i class="icon-tasks"></i> Today's Orders
                            <?php
                            $from = date('Y-m-d 00:00:00');
                            $to = date('Y-m-d 23:59:59');

                            $result = mysqli_query($con, "SELECT * FROM Orders WHERE orderDate BETWEEN '$from' AND '$to'");
                            if ($result) {
                                $num_rows1 = mysqli_num_rows($result);
                                echo '<b class="label orange pull-right">' . htmlentities($num_rows1) . '</b>';
                            } else {
                                echo '<b class="label orange pull-right">0</b>';
                            }
                            ?>
                        </a>
                    </li>
                    <li>
                        <a href="pending-orders.php">
                            <i class="icon-tasks"></i> Pending Orders
                            <?php
                            $status = 'Delivered';
                            $ret = mysqli_query($con, "SELECT * FROM Orders WHERE orderStatus IS NULL OR orderStatus != '$status'");
                            if ($ret) {
                                $num = mysqli_num_rows($ret);
                                echo '<b class="label orange pull-right">' . htmlentities($num) . '</b>';
                            } else {
                                echo '<b class="label orange pull-right">0</b>';
                            }
                            ?>
                        </a>
                    </li>
                    <li>
                        <a href="delivered-orders.php">
                            <i class="icon-inbox"></i> Delivered Orders
                            <?php
                            $rt = mysqli_query($con, "SELECT * FROM Orders WHERE orderStatus = '$status'");
                            if ($rt) {
                                $num1 = mysqli_num_rows($rt);
                                echo '<b class="label green pull-right">' . htmlentities($num1) . '</b>';
                            } else {
                                echo '<b class="label green pull-right">0</b>';
                            }
                            ?>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="manage-users.php">
                    <i class="menu-icon icon-group"></i> Manage Users
                </a>
            </li>
        </ul>

        <ul class="widget widget-menu unstyled">
            <li><a href="category.php"><i class="menu-icon icon-tasks"></i> Create Category </a></li>
            <li><a href="subcategory.php"><i class="menu-icon icon-tasks"></i> Sub Category </a></li>
            <li><a href="insert-product.php"><i class="menu-icon icon-paste"></i> Insert Product </a></li>
            <li><a href="manage-products.php"><i class="menu-icon icon-table"></i> Manage Products </a></li>
        </ul>

        <ul class="widget widget-menu unstyled">
            <li><a href="user-logs.php"><i class="menu-icon icon-tasks"></i> User Login Log </a></li>
            <li>
                <a href="logout.php">
                    <i class="menu-icon icon-signout"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>
