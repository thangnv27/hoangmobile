<?php

if (!class_exists('WP_List_Table'))
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class WPOrders_Detail_List_Table extends WP_List_Table {

    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct(array(
            'singular' => 'wp_orders_detail', //Singular label
            'plural' => 'wp_orders_details', //plural label, also this well be one of the table css class
            'ajax' => false //We won't support Ajax for this table
        ));
    }

    /**
     * Add extra markup in the toolbars before or after the list
     * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
     */
    function extra_tablenav($which) {
        if ($which == "top") {
            //The code that goes before the table is here
            echo "<h3>Danh sách sản phẩm:</h3>";
        }
        if ($which == "bottom") {
            //The code that goes after the table is there
        }
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        return $columns = array(
            'col_orders_id' => __('Product ID'),
            'col_orders_name' => __('Product Name'),
            'col_orders_price' => __('Price'),
            'col_orders_quantity' => __('Quantity'),
            'col_orders_amount' => __('Amount')
        );
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb;
        $tblOrders = $wpdb->prefix . 'orders';
        $order_id = intval($_GET['order_id']);
        
        /* -- Preparing your query -- */
        $query = "SELECT * FROM $tblOrders WHERE ID = $order_id ";

        /* -- Register the Columns -- */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);

        /* -- Fetch the items -- */
        $ordersRow = $wpdb->get_row($query);
        $this->orderID = $ordersRow->ID;
        $this->customer_id = $ordersRow->customer_id;
        $this->customer_info = json_decode($ordersRow->customer_info);
        $this->payment_method = $ordersRow->payment_method;
        $this->total_amount = $ordersRow->total_amount;
        $this->items = json_decode($ordersRow->products);
        
        $customer_info = $this->customer_info;
        echo <<<HTML
        <h3>Order ID: #{$this->orderID}</h3>
        <h3>Customer Information:</h3>
        <table>
            <tr>
                <td>Customer ID:</td>
                <td>{$this->customer_id}</td>
            </tr>
            <tr>
                <td>Fullname:</td>
                <td>{$customer_info->firstname} {$customer_info->lastname}</td>
            </tr>
            <tr>
                <td>Phone number:</td>
                <td>{$customer_info->phone}</td>
            </tr>
            <tr>
                <td>Address:</td>
                <td>{$customer_info->address}</td>
            </tr>
            <tr>
                <td>City:</td>
                <td>{$customer_info->city}</td>
            </tr>
            <tr>
                <td>District:</td>
                <td>{$customer_info->district}</td>
            </tr>
            <tr>
                <td>Ward:</td>
                <td>{$customer_info->ward}</td>
            </tr>
        </table>
        <h3>Payment Method:</h3>
        {$this->payment_method}
        <h3>Total amount: <font color='red'>$this->total_amount</font> VNĐ</h3>
HTML;
    }

    /**
     * Display the rows of records in the table
     * @return string, echo the markup of the rows
     */
    function display_rows() {

        //Get the records registered in the prepare_items method
        $records = $this->items;

        //Get the columns registered in the get_columns and get_sortable_columns methods
        list( $columns, $hidden ) = $this->get_column_info();

        //Loop for each record
        if (!empty($records)) {
            foreach ($records as $rec) {

                //Open the line
                echo '<tr id="record_' . $rec->id . '">';
                foreach ($columns as $column_name => $column_display_name) {

                    //Style attributes for each col
                    $class = "class='$column_name column-$column_name'";
                    $style = "";
                    if (in_array($column_name, $hidden))
                        $style = ' style="display:none;"';
                    $attributes = $class . $style;

                    //Display the cell
                    switch ($column_name) {
                        case "col_orders_id": echo '<td ' . $attributes . '>' . $rec->id . '</td>';
                            break;
                        case "col_orders_name": echo '<td ' . $attributes . '>' . $this->column_title($rec) . '</td>';
                            break;
                        case "col_orders_price": echo '<td ' . $attributes . '>' . stripslashes($rec->price) . ' VNĐ</td>';
                            break;
                        case "col_orders_quantity": echo '<td ' . $attributes . '>' . $rec->quantity . '</td>';
                            break;
                        case "col_orders_amount": echo '<td ' . $attributes . '>' . $rec->amount . ' VNĐ</td>';
                            break;
                    }
                }

                //Close the line
                echo'</tr>';
            }
        }
    }
    
    function column_title($item) {
        $permalink = get_permalink( $item->id );
        $actions = array(
            'edit' => sprintf('<a href="post.php?post=%s&action=edit">Edit</a>', $item->id),
            'view' => sprintf('<a href="%s">View</a>', $permalink),
        );

        return sprintf('%1$s %2$s',stripslashes( $item->title ), $this->row_actions($actions));
    }

}