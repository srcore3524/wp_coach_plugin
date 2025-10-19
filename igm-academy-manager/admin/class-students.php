<?php

class IGM_Academy_Students {

    public static function display_students_page() {
        if ( isset( $_POST['igm_action'] ) && isset( $_POST['igm_student_nonce'] ) &&
             check_admin_referer( 'igm_student_action', 'igm_student_nonce' ) ) {
            if ( $_POST['igm_action'] === 'add' || $_POST['igm_action'] === 'edit' ) {
                self::save_student();
                return;
            } elseif ( $_POST['igm_action'] === 'delete' && isset( $_POST['student_id'] ) ) {
                self::delete_student( intval( $_POST['student_id'] ) );
                return;
            }
        }

        $action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : 'list';
        $student_id = isset( $_GET['student_id'] ) ? intval( $_GET['student_id'] ) : 0;

        if ( $action === 'edit' && $student_id > 0 ) {
            self::display_edit_form( $student_id );
        } elseif ( $action === 'add' ) {
            self::display_add_form();
        } else {
            self::display_students_list();
        }
    }

    private static function display_students_list() {
        global $wpdb;
        $search = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

        $where = '';
        if ( ! empty( $search ) ) {
            $where = $wpdb->prepare(
                " WHERE first_name LIKE %s OR last_name LIKE %s OR email LIKE %s",
                '%' . $wpdb->esc_like( $search ) . '%',
                '%' . $wpdb->esc_like( $search ) . '%',
                '%' . $wpdb->esc_like( $search ) . '%'
            );
        }

        $students = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}igm_students $where ORDER BY last_name, first_name" );
        $total_students = count( $students );

        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/students-list.php';
    }

    private static function display_add_form() {
        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/student-form.php';
    }

    private static function display_edit_form( $student_id ) {
        global $wpdb;
        $student = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}igm_students WHERE id = %d",
            $student_id
        ) );

        if ( ! $student ) {
            wp_die( __( 'Student not found.', 'igm-academy-manager' ) );
        }

        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/student-form.php';
    }

    private static function save_student() {
        global $wpdb;
        $student_id = isset( $_POST['student_id'] ) ? intval( $_POST['student_id'] ) : 0;

        $data = array(
            'first_name'      => sanitize_text_field( $_POST['first_name'] ),
            'last_name'       => sanitize_text_field( $_POST['last_name'] ),
            'email'           => sanitize_email( $_POST['email'] ),
            'phone'           => sanitize_text_field( $_POST['phone'] ),
            'gender'          => sanitize_text_field( $_POST['gender'] ),
            'birth_date'      => ! empty( $_POST['birth_date'] ) ? sanitize_text_field( $_POST['birth_date'] ) : null,
            'notes'           => sanitize_textarea_field( $_POST['notes'] ),
            'total_classes'   => intval( $_POST['total_classes'] ),
            'pending_classes' => intval( $_POST['pending_classes'] ),
        );

        $format = array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d' );

        if ( $student_id > 0 ) {
            $wpdb->update( "{$wpdb->prefix}igm_students", $data, array( 'id' => $student_id ), $format, array( '%d' ) );
        } else {
            $wpdb->insert( "{$wpdb->prefix}igm_students", $data, $format );
        }

        wp_redirect( add_query_arg( array( 'page' => 'igm-students', 'message' => 'success' ), admin_url( 'admin.php' ) ) );
        exit;
    }

    private static function delete_student( $student_id ) {
        global $wpdb;
        $wpdb->delete( $wpdb->prefix . 'igm_students', array( 'id' => $student_id ), array( '%d' ) );
        wp_redirect( add_query_arg( array( 'page' => 'igm-students', 'message' => 'deleted' ), admin_url( 'admin.php' ) ) );
        exit;
    }
}
