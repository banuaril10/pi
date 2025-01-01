<?php
include "../../config/koneksi.php";

$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}
function get_category($url)
{
    $curl = curl_init();
    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        )
    );

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}


$jum = 0;
$check = $connec->query("select count(ad_morg_key) jum from ad_morg where ad_morg_key = '" . $locationid . "'");
foreach ($check as $row) {
    $jum = $row["jum"];
}

if ($jum == 0) {
    $url = $base_url . '/store/users/get_users_it.php';

    // echo $idstore;

    // $hasil = get_category($url);
    // $j_hasil = json_decode($hasil, true);

    // $json = array(
    //     "status" => "OK",
    //     "data" => $j_hasil
    // );

    // echo json_encode($json);


    $j_hasil = '{"status":"OK","data":[{"id_user":"C3DA7B7F76AC430897531621291542A4","id_location":null,"insertdate":"2024-10-28 07:15:42","insertby":"iv","postby":"iv","postdate":"2024-10-28 07:15:42","id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","fullname":"Testing IN - 01","username":"test-user-01","avatar":"UserNoFrame.png","password":"67e6d3f13fdd0ad6dd97dd0fde16362f66fc114fbc141672ffb01cc92d7b739a","email":null,"phone":"10100110","description":null,"is_active":"1","accesscode":"35","unicode":"25","auth_key":"da8647963ebca88e3254632cd2ed1a86","access_token":"d6ad6a2a9d0909c9a7303b3dabf32f08"},{"id_user":"E3BF8D790EE24E7FBA8300D239791380","id_location":null,"insertdate":"2024-10-30 13:14:05.306588","insertby":null,"postby":null,"postdate":null,"id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","fullname":"Tommy Zulkhaidir","username":"11166","avatar":"UserNoFrame.png","password":"a298fcf74a4db1bbf82f8f9971d09f319e11066c612e7df4d0768f9b8d3083ff","email":null,"phone":"081319010432","description":null,"is_active":"1","accesscode":"1","unicode":"1","auth_key":null,"access_token":null},{"id_user":"7ad5141cd9ac424483a1687d4d4b8663","id_location":null,"insertdate":"2024-08-12 09:34:06.946","insertby":"IRFAN","postby":"IRFAN","postdate":"2024-08-12 09:34:06.946","id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","fullname":"Banu","username":"banu","avatar":"UserNoFrame.png","password":"0c151eda7f88d60345c1eaf4cb622532abad2bda6b24876999a8c092fa5092c8","email":"","phone":"088211832975","description":"","is_active":"1","accesscode":null,"unicode":null,"auth_key":"dbM89Sv1VftfXUeAPxeK1wZKyRU1ZuT9","access_token":"JfJUp7MLb6uCUd3ZvafM9OVq5bkkIikW"},{"id_user":"E05BD54B73A74BC5919B2A7541063627","id_location":null,"insertdate":"2024-12-31 15:27:02","insertby":"iv","postby":"iv","postdate":"2024-12-31 15:27:02","id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","fullname":"Yusup Alfarizi","username":"171751","avatar":"UserNoFrame.png","password":"3b42ad7d28df89e97b8c9ef4c3a3cb53ba8fb5cb0ac93b11c2fd3045b66484a9","email":null,"phone":"087720929268","description":null,"is_active":"1","accesscode":null,"unicode":null,"auth_key":"da8647963ebca88e3254632cd2ed1a86","access_token":"d6ad6a2a9d0909c9a7303b3dabf32f08"},{"id_user":"b07e559075a241df8973ea5bfc9574fc","id_location":null,"insertdate":"2024-08-12 09:34:06.946","insertby":"IRFAN","postby":"IRFAN","postdate":"2024-08-12 09:34:06.946","id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","fullname":"Nada F","username":"nada","avatar":"UserNoFrame.png","password":"ad97c8aafa71436942e5d0462f12bc8af44b53806e7dac69e8a597e2efed65f4","email":"","phone":"081316830855","description":"","is_active":"1","accesscode":null,"unicode":null,"auth_key":"88Dnv4I4RFcabH6SLWsJjcRUKes58dv1","access_token":"KG76lVARX5asrVp0dybjTRyQthnFJqRH"},{"id_user":"4080B0F70F7347E995B7623F4CDE15F8","id_location":null,"insertdate":"2024-12-31 15:28:00","insertby":"iv","postby":"iv","postdate":"2024-12-31 15:28:00","id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","fullname":"Muhammad Fiqi","username":"151132","avatar":"UserNoFrame.png","password":"39f497830f0a5e56dc9334e0a811cb16d01dc7dd3b0b2f234ee2848025197a1b","email":null,"phone":"085842977337","description":null,"is_active":"1","accesscode":null,"unicode":null,"auth_key":"da8647963ebca88e3254632cd2ed1a86","access_token":"d6ad6a2a9d0909c9a7303b3dabf32f08"},{"id_user":"133CA43217B24FAE89A6D11055B97953","id_location":null,"insertdate":"2024-11-20 04:00:51","insertby":"banu","postby":"banu","postdate":"2024-11-20 04:00:51","id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","fullname":"agil","username":"09056","avatar":"UserNoFrame.png","password":"9750523f340c5a9c7fdabea9d168bf6e6ea4c16ee36ee0358d969625a95b2a7d","email":null,"phone":"085778201406","description":null,"is_active":"1","accesscode":"59","unicode":"46","auth_key":"45f59e3b71bcdb2a5046d8fc07a4354f","access_token":"556ed95593d9f478b65ce7a3742947e6"},{"id_user":"2da1987b6f18406ab0db5e2f11fb0866","id_location":null,"insertdate":"2024-08-12 09:34:06.946","insertby":"IRFAN","postby":"IRFAN","postdate":"2024-08-12 09:34:06.946","id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","fullname":"Ihsan N","username":"ihsan","avatar":"UserNoFrame.png","password":"418f77de56276ae7a00bebb80771aee109fb2d90a5119dd3ae27e4cde5620f85","email":"","phone":"081906850097","description":"","is_active":"1","accesscode":"1","unicode":"1","auth_key":"cU1BFEQkSz1EQIxcQPfuqBbn37F8qpBC","access_token":"6NR8aOPbWFp2jKaNStnI2MaHYrtCg01T"},{"id_user":"cefd1e1700d0419fa12d1e8618229339","id_location":null,"insertdate":"2024-08-12 09:34:06.946","insertby":"IRFAN","postby":"IRFAN","postdate":"2024-08-12 09:34:06.946","id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","fullname":"Irham Fzi","username":"iam_fzi","avatar":"UserNoFrame.png","password":"62ca91d1f8318cbb0e05fc5698b6ce1605a348b1f3881b036db444e49511e2fc","email":"","phone":"08567770002","description":"","is_active":"1","accesscode":null,"unicode":null,"auth_key":"OMfuqQO3AXtLIE5RqkOIyd7TeRUaq9Hq","access_token":"GMMtGGj9aFpyS514F75qA889somJHYdZ"}]}
{"status":"OK","data":[{"id_user":"C3DA7B7F76AC430897531621291542A4","id_location":null,"insertdate":"28-10-2024 07:15:42","insertby": "iv","postby":"iv","tanggal posting":"28-10-2024 07:15:42","id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","fullname":"Pengujian DALAM - 01","nama pengguna":"pengguna-penguji-01","avatar":"UserNoFrame.png","kata sandi":"67e6d3f13fdd0ad6dd97dd0fde16362f66fc 114fbc141672ffb01cc92d7b739a","email":null,"telepon":"10100110","description":null,"is_active":"1","accessc ode":"35","unicode":"25","auth_key":"da8647963ebca88e3254632cd2ed1a86","access_token":"d6ad6a2a9d0909c9a7 303b3dabf32f08"},{"id_user":"E3BF8D790EE24E7FBA8300D239791380","id_location":null,"insertdate":"30-10-2024 13:14:05.306588","insertby":null,"postby":null,"postdate":null,"id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","fullname":"Tommy Zulkhaidir","nama pengguna":"11166","avatar":"UserNoFrame.png","password":"a298fcf74a4db1bbf82f8 f9971d09f319e11066c612e7df4d0768f9b8d3083ff","email":null,"telepon":"081319010432","deskripsi on":null,"is_active":"1","accesscode":"1","unicode":"1","auth_key":null,"access_token":null},{"id_user":"7ad5141cd9ac424483a1687d4d4b8663" ,"id_location":null,"insertdate":"12-08-2024 09:34:06.946","insertby":"IRFAN","postby":"IRFAN","postdate":"12-08-2024 09:34:06.946","id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","nama lengkap":"Banu","nama pengguna":"banu","avatar":"UserNoFrame.p ng","kata sandi":"0c151eda7f88d60345c1eaf4cb622532abad2bda6b24876999a8c092fa5092c8","email":"","telepon":"088211832975","deskripsi ption":"","is_active":"1","accesscode":null,"unicode":null,"auth_key":"dbM89Sv1VftfXUeAPxeK1wZKyRU1ZuT9","access_token":"J fJUp7MLb6uCUd3ZvafM9OVq5bkkIikW"},{"id_user":"E05BD54B73A74BC5919B2A7541063627","id_location":null,"insertdate":"31-12-2024 15:27:02","insertby":"iv","postby":"iv","postdate":"31-12-2024 15:27:02","id_role":"2f9f5a6a-d183-4887 -a1a0-8efca0800e9e","nama lengkap":"Yusup Alfarizi","nama pengguna":"171751","avatar":"UserNoFrame.png","kata sandi":"3b42ad7d28df89e97b8c9ef4c3a3cb53ba8fb 5cb0ac93b11c2fd3045b66484a9","email":null,"telepon":"087720929268","description":null,"is_active":"1","akses scode":null,"unicode":null,"auth_key":"da8647963ebca88e3254632cd2ed1a86","access_token":"d6ad6a2a9d0909c9a 7303b3dabf32f08"},{"id_user":"b07e559075a241df8973ea5bfc9574fc","id_location":null,"insertdate":"12-08-2024 09:34:06.946","insertby":"IRFAN","postby":"IRFAN","postdate":"12-08-2024 09:34:06.946","id_role":"2f9f5a6a-d183-4887 -a1a0-8efca0800e9e","nama lengkap":"Nada F","nama pengguna":"nada","avatar":"UserNoFrame.png","password":"ad97c8aafa71436942e5d0462f12bc8af44b53806e7 dac69e8a597e2efed65f4","email":"","telepon":"081316830855","description":"","is_active":"1","kode akses": null,"unicode":null,"auth_key":"88Dnv4I4RFcabH6SLWsJjcRUKes58dv1","access_token":"KG76lVARX5asrVp0dybjT RyQthnFJqRH"},{"id_user":"4080B0F70F7347E995B7623F4CDE15F8","id_location":null,"insertdate":"31-12-2024 15:28:00","insertby":"iv","postby":"iv","postdate":"31-12-2024 15:28:00","id_role":"2f9f5a6a-d183-4887 -a1a0-8efca0800e9e","nama lengkap":"Muhammad Fiqi","nama pengguna":"151132","avatar":"UserNoFrame.png","kata sandi":"39f497830f0a5e56dc9334e0a811cb16d01dc7dd 3b0b2f234ee2848025197a1b","email":null,"telepon":"085842977337","description":null,"is_active":"1","accessc ode":null,"unicode":null,"auth_key":"da8647963ebca88e3254632cd2ed1a86","access_token":"d6ad6a2a9d0909c9a7 303b3dabf32f08"},{"id_user":"133CA43217B24FAE89A6D11055B97953","id_location":null,"insertdate":"20-11-2024 04:00:51","insertby":"banu","postby":"banu","postdate":"20-11-2024 04:00:51","id_role":"2f9f5a6a-d183-4887-a1a0-8efca0800e9e","nama lengkap":"agil","nama pengguna":"09056","avatar":"UserNoFrame.png" ,"kata sandi":"9750523f340c5a9c7fdabea9d168bf6e6ea4c16ee36ee0358d969625a95b2a7d","email":null,"telepon":"085778201406","deskripsi tion":null,"is_active":"1","accesscode":"59","unicode":"46","auth_key":"45f59e3b71bcdb2a5046d8fc07a4354f","access_token":"5 56ed95593d9f478b65ce7a3742947e6"},{"id_user":"2da1987b6f18406ab0db5e2f11fb0866","id_location":null,"insertdate":"12-08-2024 09:34:06.946","insertby":"IRFAN","postby":"IRFAN","postdate":"12-08-2024 09:34:06.946","id_role":"2f9f5a6a-d183-4887 -a1a0-8efca0800e9e","nama lengkap":"Ihsan N","nama pengguna":"ihsan","avatar":"UserNoFrame.png","kata sandi":"418f77de56276ae7a00bebb80771aee109fb2d90a 5119dd3ae27e4cde5620f85","email":"","telepon":"081906850097","description":"","is_active":"1","kode akses ":"1","unicode":"1","auth_key":"cU1BFEQkSz1EQIxcQPfuqBbn37F8qpBC","access_token":"6NR8aOPbWFp2jKaNStnI2 MaHYrtCg01T"},{"id_user":"cefd1e1700d0419fa12d1e8618229339","id_location":null,"insertdate":"12-08-2024 09:34:06.946","insertby":"IRFAN","postby":"IRFAN","postdate":"12-08-2024 09:34:06.946","id_role":"2f9f5a6a-d183-4887 -a1a0-8efca0800e9e","nama lengkap":"Irham Fzi","namapengguna":"iam_fzi","avatar":"UserNoFrame.png","kata sandi":"62ca91d1f8318cbb0e05fc5698b6ce1605a348b1f3881b036db444e49511e2fc","email":"","telepon":"08567770002", "deskripsi":"","is_active":"1","accesscode":null,"unicode":null,"auth_key":"OMfuqQO3AXtLIE5RqkOIyd7TeRUaq9Hq","access_token":"GMMtGGj9aFpyS514F75qA889somJHYdZ"}]}
';

    //convert string to json
    // $data = json_decode($json_string, true); 


    // $jsonData = json_encode($myarray);
    // $j_hasil = json_decode($jsonData, true);

    // $json = array(
    //     "status" => "OK",
    //     "message" => $j_hasil,
    // );
    echo json_encode($j_hasil);



} else {

    $id_user = "pos";
    $id_location = $idstore;
    $insertdate = date('Y-m-d H:i:s');
    $insertby = "pos";
    $postby = "pos";
    $postdate = date('Y-m-d H:i:s');
    $id_role = "2f9f5a6a-d183-4887-a1a0-8efca0800e9e";
    $fullname = "POS";
    $username = "pos";
    $avatar = "pos";
    $password = "pos";
    $email = "pos";
    $phone = "pos";
    $description = "pos";
    $is_active = "Y";
    $accesscode = "pos";
    $unicode = "pos";
    $auth_key = "pos";
    $access_token = "pos";


    $myarray = array();
    $myarray[] = array(
        'id_user' => $id_user,
        'id_location' => $idstore,
        'insertdate' => $insertdate,
        'insertby' => $insertby,
        'postby' => $postby,
        'postdate' => $postdate,
        'id_role' => $id_role,
        'fullname' => $fullname,
        'username' => $username,
        'avatar' => $avatar,
        'password' => $password,
        'email' => $email,
        'phone' => $phone,
        'description' => $description,
        'is_active' => $is_active,
        'accesscode' => $accesscode,
        'unicode' => $unicode,
        'auth_key' => $auth_key,
        'access_token' => $access_token
    );

    $jsonData = json_encode($myarray);
    $j_hasil = json_decode($jsonData, true);

    $json = array(
        "status" => "OK",
        "message" => $j_hasil,
    );
    echo json_encode($json);
}





?>