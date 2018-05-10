<div class="subnavbar">
    <div class="subnavbar-inner">
        <div class="container">
            <ul class="mainnav">
                <li<?php echo $page_name=='Dashboard' ? ' class="active"' : ''; ?>>
                    <a href="<?php echo SITE_URL; ?>admin/dashboard/"><i class="icon-dashboard"></i><span>Dashboard</span></a>
                </li>
                <li<?php echo $page_name=='Influencer Categories' ? ' class="active"' : ''; ?>>
                    <a href="<?php echo SITE_URL; ?>admin/influencer-categories/"><i class="icon-list-alt"></i><span>Influencer Category</span></a>
                </li>
                <li<?php echo $page_name=='Product Categories' ? ' class="active"' : ''; ?>>
                    <a href="<?php echo SITE_URL; ?>admin/product-categories/"><i class="icon-bar-chart"></i><span>Product Category</span></a>
                </li>
				<li<?php echo $page_name=='Products' ? ' class="active"' : ''; ?>>
                    <a href="<?php echo SITE_URL; ?>admin/products/"><i class="icon-bar-chart"></i><span>Products</span></a>
                </li>
                <li<?php echo $page_name=='Buzznews Blogs' ? ' class="active"' : ''; ?>>
                    <a href="<?php echo SITE_URL; ?>admin/blogs/"><i class="icon-bar-chart"></i><span>Buzznews Blogs</span></a>
                </li>
				<li<?php echo $page_name=='Cancellation Reason' ? ' class="active"' : ''; ?>>
                    <a href="<?php echo SITE_URL; ?>admin/cancellation-reason/"><i class="icon-bar-chart"></i><span>Unsubscribe Reason</span></a>
                </li>
                <li></li>
            </ul>
        </div>
    </div>
</div>