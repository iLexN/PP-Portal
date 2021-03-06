<?php

//General
$app->get('/office-info', 'PP\Portal\Controller\General\OfficeInfo')
        ->setName('OfficeInfo');

$app->post('/verify', 'PP\Portal\Controller\User\Verify')
        ->setName('UserVerify');
$app->get('/check-username/{user_name}', 'PP\Portal\Controller\User\CheckUserName')
        ->setName('CheckUserName');

$app->post('/login', 'PP\Portal\Controller\User\Login')
        ->setName('UserLogin');
$app->post('/forgot-passowrd', 'PP\Portal\Controller\User\ForgotPassword')
        ->setName('ForgotPassword');
$app->get('/forgot-passowrd/{token}', 'PP\Portal\Controller\User\ForgotPasswordToken')
        ->setName('ForgotPassword.Token');
$app->post('/forgot-passowrd/{token}', 'PP\Portal\Controller\User\ForgotPasswordTokenUpdate')
        ->setName('ForgotPassword.TokenUpdate');
$app->post('/forgot-username', 'PP\Portal\Controller\User\ForgotUsername')
        ->setName('ForgotUsername');

//User
$app->get('/user/{id:\d+}', 'PP\Portal\Controller\User\Info')
        ->setName('UserInfo')
        ->add($checkUserExist);
$app->post('/user/{id:\d+}', 'PP\Portal\Controller\User\InfoUpdate')
        ->setName('UserInfo.Post')
        ->add($checkUserExist);
$app->get('/user/{id:\d+}/renew', 'PP\Portal\Controller\User\RenewInfo')
        ->setName('UserInfo.Renew')
        ->add($checkUserExist);
$app->get('/user/{id:\d+}/people', 'PP\Portal\Controller\User\People')
        ->setName('UserInfo.People')
        ->add($checkUserExist);

$app->post('/user/{id:\d+}/signup', 'PP\Portal\Controller\User\Signup')
        ->setName('UserSignUp')
        ->add($checkUserExist);

$app->get('/user/{id:\d+}/policy', 'PP\Portal\Controller\Policy\PolicyList')
        ->setName('PolicyList')
        ->add($checkUserExist);
$app->post('/user/{id:\d+}/change-passowrd', 'PP\Portal\Controller\User\ChangePassword')
        ->setName('UserChangePassword')
        ->add($checkUserExist);
$app->get('/user/{id:\d+}/bank-account', 'PP\Portal\Controller\User\BankAccInfo')
        ->setName('BankAccInfo')
        ->add($checkUserExist);
$app->post('/user/{id:\d+}/bank-account', 'PP\Portal\Controller\User\BankAccActionUpdate')
        ->setName('BankAccAction.new')
        ->setArgument('mode', 'create')
        ->add($checkUserExist);
$app->post('/user/{id:\d+}/bank-account/{acid:\d+}', 'PP\Portal\Controller\User\BankAccActionUpdate')
        ->setName('BankAccAction.edit')
        ->setArgument('mode', 'update')
        ->add($checkUserExist);
$app->delete('/user/{id:\d+}/bank-account/{acid:\d+}', 'PP\Portal\Controller\User\BankAccActionDel')
        ->setName('BankAccAction.del')
        ->add($checkUserExist);
$app->get('/user/{id:\d+}/preference', 'PP\Portal\Controller\User\UserPreferenceInfo')
        ->setName('UserPreference.get')
        ->add($checkUserExist);
$app->post('/user/{id:\d+}/preference', 'PP\Portal\Controller\User\UserPreferenceUpdate')
        ->setName('UserPreference.update')
        ->add($checkUserExist);
$app->get('/user/{id:\d+}/address', 'PP\Portal\Controller\User\AddressList')
        ->setName('addresslist.get')
        ->add($checkUserExist);
$app->post('/user/{id:\d+}/address', 'PP\Portal\Controller\User\AddressNew')
        ->setName('addresslist.new')
        ->add($checkUserExist);
$app->post('/user/{id:\d+}/address/{acid:\d+}', 'PP\Portal\Controller\Address\AddressUpdate')
        ->setName('addresslist.update')
        ->setArgument('mode', 'User')
        ->add($checkUserExist);
$app->delete('/user/{id:\d+}/address/{acid:\d+}', 'PP\Portal\Controller\Address\AddressDelete')
        ->setName('addresslist.delete')
        ->add($checkUserExist);

$app->get('/advisor/{id:\d+}', 'PP\Portal\Controller\Advisor\Info')
        ->setName('AdvisorInfo');

//Holder
$app->get('/holder/{id:\d+}', 'PP\Portal\Controller\Holder\Info')
        ->setName('HolderInfo.get');
$app->post('/holder/{id:\d+}', 'PP\Portal\Controller\Holder\Update')
        ->setName('HolderInfo.update');
$app->get('/holder/{id:\d+}/renew', 'PP\Portal\Controller\Holder\RenewInfo')
        ->setName('HolderInfoRenew.get');

//Policy
$app->get('/policy/{id:\d+}/people', 'PP\Portal\Controller\Policy\People')
        ->setName('Policy.People');
//file download
$app->get('/policy/{name:policy-file|plan-file}/{id:\d+}', 'PP\Portal\Controller\Policy\FileDownload')
        ->setName('File.Download');

//UserPolicy
$app->get('/user-policy/{id:\d+}/policy', 'PP\Portal\Controller\Policy\PolicyInfo')
        ->setName('Policy')
        ->add($checkUsePolicyrExist);
$app->get('/user-policy/{id:\d+}/zip', 'PP\Portal\Controller\Policy\ZipFiles')
        ->setName('UserPolicy.ZipFiles')
        ->add($checkUsePolicyrExist);
/* seem no use any more, move to holder info
$app->get('/user-policy/{id:\d+}/address', 'PP\Portal\Controller\Policy\AddressList')
        ->setName('PolicyAddressList.get')
        ->add($checkUsePolicyrExist);
$app->post('/user-policy/{id:\d+}/address/{acid:\d+}', 'PP\Portal\Controller\Address\AddressUpdate')
        ->setName('PolicyAddressInfo.update')
        ->setArgument('mode', 'UserPolicy')
        ->add($checkUsePolicyrExist);
 */
$app->get('/user-policy/{id:\d+}/claim', 'PP\Portal\Controller\Claim\ClaimList')
        ->setName('ClaimList')
        ->add($checkUsePolicyrExist);
$app->post('/user-policy/{id:\d+}/claim', 'PP\Portal\Controller\Claim\ClaimUpdate')
        ->setName('ClaimCreate')
        ->setArgument('mode', 'create')
        ->add($checkUsePolicyrExist);
//Claim
$app->get('/claim/{id:\d+}', 'PP\Portal\Controller\Claim\ClaimInfo')
        ->setName('ClaimInfo')
        ->add($checkClaimExist);
$app->post('/claim/{id:\d+}', 'PP\Portal\Controller\Claim\ClaimUpdate')
        ->setName('ClaimUpdate')
        ->setArgument('mode', 'update')
        ->add($checkClaimExist);
$app->post('/claim/{id:\d+}/attachment', 'PP\Portal\Controller\Claim\ClaimAttachment')
        ->setName('ClaimAttachment')
        ->add($checkClaimExist);
$app->get('/attachment/{id:\d+}', 'PP\Portal\Controller\Claim\AttachmentDownload')
        ->setName('AttachmentDownload');
$app->post('/attachment/{id:\d+}', 'PP\Portal\Controller\Claim\AttachmentDel')
        ->setName('AttachmentDel');

//$app->get('/test/token', 'PP\Portal\Controller\Test\Token')
//        ->setName('TestToken')
//        ->add($checkToken);

//helper for development
$app->get('/helper/router', 'PP\Portal\Controller\Helper\Router')
        ->setName('helperRouter');
$app->get('/helper/code', 'PP\Portal\Controller\Helper\Code')
        ->setName('helperCode');
