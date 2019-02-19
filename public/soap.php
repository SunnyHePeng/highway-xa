<?php
header("content-type:text/html;charset=utf-8");
/*try {
    $client = new SoapClient("http://220.169.236.108:7001/LoginService/Service1.asmx?wsdl");
    print_r($client->__getFunctions());
    print_r($client->__getTypes());  
} catch (SOAPFault $e) {
    print $e;
}*/

$client = new SoapClient("http://220.169.236.108:7001/LoginService/Service1.asmx?wsdl");
$result = $client->GetUserRoleList();
//var_dump($result);
$userName = $result->GetUserRoleListResult; 
echo $userName;
$result = $client->Encrypt(array('s'=>'122'));
//var_dump($result);
$userName = $result->EncryptResult;
echo $userName;
/*$result = $client->Encrypt(array('s'=>'123456'));
$userAccount = $result->EncryptResult;

$result = $client->Encrypt(array('s'=>'123456'));
$password = $result->EncryptResult;

$result = $client->Encrypt(array('s'=>'2'));
$userrole = $result->EncryptResult;*/
/*$params = array(  
    'userName' => $userName,  
    'userAccount' => $userAccount,  
    'password'=> $password,  
    'userrole'=> $userrole 
); 
$result=$client->SyncUser($params);  
echo $result->SyncUserResult;//显示结果  */
?>