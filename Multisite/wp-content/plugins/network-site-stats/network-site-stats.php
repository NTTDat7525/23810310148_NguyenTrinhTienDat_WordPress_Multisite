<?php
/*
Plugin Name: Network Site Stats
Description: Hiển thị thống kê các site trong mạng lưới.
Version: 1.0
Author: Nguyễn Trịnh Tiến Đạt
Network: true
*/

// thêm menu vào Network Admin
add_action('network_admin_menu', function() {
    add_menu_page(
        'Network Site Stats',
        'Site Stats',
        'manage_network',
        'network-site-stats',
        'nss_render_page'
    );
});

// render trang thống kê
function nss_render_page() {
    echo '<div class="wrap"><h1>Thống kê các Site</h1>';
    echo '<table class="widefat"><thead><tr>
            <th>ID</th><th>Tên Site</th><th>Số bài viết</th><th>Ngày đăng mới nhất</th>
          </tr></thead><tbody>';

    $sites = get_sites();
    foreach ($sites as $site) {
        $blog_id = $site->blog_id;
        switch_to_blog($blog_id);

        $post_count = wp_count_posts()->publish;
        $latest_post = get_posts([
            'numberposts' => 1,
            'orderby' => 'date',
            'order' => 'DESC'
        ]);
        $latest_date = $latest_post ? $latest_post[0]->post_date : 'N/A';

        echo "<tr>
                <td>{$blog_id}</td>
                <td>" . get_bloginfo('name') . "</td>
                <td>{$post_count}</td>
                <td>{$latest_date}</td>
              </tr>";

        restore_current_blog();
    }

    echo '</tbody></table></div>';
}