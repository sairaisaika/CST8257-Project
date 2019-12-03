<?php
function ValidatePrincipal($amount){
    if(!$amount||!is_numeric($amount)||$amount<=0)
        return False;
    else {
        return True;
    }
}
function ValidateRate($amount){
    if(!$amount||!is_numeric($amount)||$amount<0)
        return False;
    return True;
}
function ValidateYears($years){
    if(!$years||(!is_numeric($years))||$years<=0||$years>20)
        return False;
    return True;
}
function ValidateName($name){
    if(!$name)
        return False;
    return True;
}
function ValidatePostCode($code){
    $codeRegex = "/[a-z][0-9][a-z]\s*[0-9][a-z][0-9]/i";
    if(!$code||!preg_match($codeRegex, $code))//•	Postal code must be in the form of  XnX nXn, where X is an upper or lower case letter and n is a digit from 0 to 9, with or without space between the first 3 characters and the last 3 characters.
        return False;
    return True;
}
function ValidatePhone($phone){
    $phoneRegex = "/[2-9][0-9][0-9]-[2-9][0-9][0-9]-[0-9][0-9][0-9][0-9]/";
    if(!$phone||!preg_match($phoneRegex, $phone)) //•	Phone number must be in the form of nnn-nnn-nnnn where n is a digit, the first n in the first and the second 3-digit groups cannot be 0 or 1.
        return False;
    return True;
}
function ValidateEmail($email){
    $emailRegex = "/\w+@\w+\.\w+/i";
    if(!$email||!preg_match($emailRegex, $email)) // •	Email must be in the form of aaa@xxx.yyy where aaa and xxx is a character (including dot “.”) string of any length, yyy is a 2 to 4 characters string.
        return False;
    return True;
}
function ValidatePassword($password){
    if (strlen($password) < 6||!preg_match("#[0-9]+#", $password)||!preg_match("#[a-z]+#", $password)||!preg_match("#[A-Z]+#", $password)) {
        return False;
    }else
    return True;
}

?>
