<?php
/**
 * Plugin Name: Shinobi Security Notifier
 * Plugin URI: https://wpshinobi.com/shinobi-security-notifier-advanced-documentation/
 * Description: Monitors the WordPress Plugin Repository and alerts administrators when any installed extension has been closed or removed.
 * Version: 1.0.0
 * Author: The Wizer Shinobi
 * Author URI: https://wpshinobi.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: shinobi-security-notifier
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Shinobi_Security_Notifier {
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_notices', array($this, 'check_extensions_status'));
        add_action('wp_ajax_check_extensions_status', array($this, 'ajax_check_extensions_status'));
        add_action('admin_init', array($this, 'schedule_daily_check'));
        add_action('shinobi_security_check', array($this, 'check_extensions_status'));
    }

    public function add_settings_page() {
        add_options_page('Security Notifier', 'Shinobi Security Notifier', 'manage_options', 'security-notifier', array($this, 'settings_page'));
    }

    public function settings_page() {
        echo '<div class="wrap"><h1>Shinobi Security Notifier</h1>';
        echo '<p>This tool automatically notifies you if any installed extension has been removed from the WordPress Extension Repository.</p>';
        echo '<button id="check_now" class="button button-primary">Check Now</button>';
        echo '<div id="check_results"></div>';
        echo '</div>';
        echo '<script>
            document.getElementById("check_now").addEventListener("click", function() {
                fetch(ajaxurl + "?action=check_extensions_status").then(response => response.text()).then(data => {
                    document.getElementById("check_results").innerHTML = esc_html(data);
                });
            });
        </script>';
    }

    public function check_extensions_status() {
        $extensions = get_plugins();
        $inactive_extensions = [];
        
        foreach ($extensions as $file => $extension) {
            $slug = dirname($file);
            $api_url = "https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&slug=" . sanitize_text_field($slug);
            $response = wp_remote_get($api_url);
            
            if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
                $inactive_extensions[] = esc_html($extension['Name']);
            }
        }
        
        if (!empty($inactive_extensions)) {
            $message = "Warning: The following extensions are no longer available in the WordPress Extension Repository: " . implode(", ", $inactive_extensions);
            update_option('shinobi_security_notifier_alert', esc_html($message));
            $this->send_email_notification($message);
        } else {
            update_option('shinobi_security_notifier_alert', '');
        }
    }

    public function send_email_notification($message) {
        $admin_email = sanitize_email(get_option('admin_email'));
        wp_mail($admin_email, 'Security Alert: Removed Extension', esc_html($message));
    }

    public function ajax_check_extensions_status() {
        check_admin_referer('shinobi_nonce');
        $this->check_extensions_status();
        echo esc_html(get_option('shinobi_security_notifier_alert'));
        wp_die();
    }

    public function schedule_daily_check() {
        if (!wp_next_scheduled('shinobi_security_check')) {
            wp_schedule_event(time(), 'daily', 'shinobi_security_check');
        }
    }
}

Shinobi_Security_Notifier::get_instance();
