function mysqlCleanOpen()
    if (Network.mysql('status') == 0)
    else
        Network.mysql('closeall'); %Close all MySQL connections. Consistently doing this helps keep the connection space clean.
        Network.mysql('open', '127.0.0.1', 'aqua2_cloud_logic', 'aqua2_cloud_logic_pass' );
        Network.mysql('use AQuA2_Cloud_Database'); %Select the correct database
    end
end