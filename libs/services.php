<?php
require '../autoload.php';
$Config = new Config();


$datas = array();

$available_protocols = array('tcp', 'udp', 'unix');

$show_port = $Config->get('services:show_port');

if (count($Config->get('services:list')) > 0)
{
    foreach ($Config->get('services:list') as $service)
    {
        $host     = $service['host'];
        $port     = $service['port'];
        $name     = $service['name'];
        $protocol = isset($service['protocol']) && in_array($service['protocol'], $available_protocols) ? $service['protocol'] : 'tcp';

        $socketProtocols = array('tcp', 'udp', 'unix');
        if (in_array($protocol, $socketProtocols)) {
            if (Misc::socketAvailable($host, $port, $protocol))
                $status = 1;
            else
                $status = 0;
        }
        else {
            $status = Misc::systemdServiceActive($host) ? 1 : 0;
        }

        $datas[] = array(
            'port'      => $show_port === true ? $port : '',
            'name'      => $name,
            'status'    => $status,
        );
    }
}


echo json_encode($datas);