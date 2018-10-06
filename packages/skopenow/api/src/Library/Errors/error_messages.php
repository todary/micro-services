<?php

return array(

    'forbidden'=> array(
        30 => [400,'Forbidden - The Skopenow API requested is hidden for registrants only.']
    ),

    'error_json' => array(
        40 => [400,'Your input is not a valid JSON format.']
    ),

    'error_auth'=> array(
        100 => [401,'Username you entered is incorrect.'],
        120 => [401,'Api key you entered is incorrect.'],
        130 => [401,'Your account is inactive.'],
        131 => [401,'Your account is blocked.'],
    ),

    'required' => array(
        200 => [400, 'Missing parameter.'],
        220 => [400, 'You should have to make results or summary assign true']
    ),

    'error_field' => array(
        310 => [400,'Too many inputs of the same type.'],
        320 => [400,'To complete a search please enter at least one of the following: Name and Location, Phone, Address, Email, or Username.'],
        324 => [400,"A person's name must consist of letters only and consist of (first name + last name) or (first name + middle name + last name)."],
        325 => [400,"Please enter the person's first and last name."],
        330 => [400,'Please enter a valid city and state.'],
        335 => [400,'Please enter an address in the following format, 42 Maple Street, New York City, New York 10001.'],
        340 => [400,'Please enter a valid phone number (ex. 1-800-888-8888), all entries must be 10 digits long.'],
        350 => [400,'Please enter a valid birthdate in the following format (mm/dd/yyyy).'],
        355 => [400,'Please enter a valid age.'],
        360 => [400,'Please enter a valid email. (ex. example@example.com).'],
        370 => [400,'Please fill out the file number before conducting a search.'],
        380 => [400,'Please enter a job in the following format (Position, Company) Or (only Company) ex. Software Engineer, Skopenow Or ex. Skopenow.'],
        390 => [400,'Please enter a valid user-name without special characters.'],
        391 => [400,'Since your search criteria is very broad your results will be hard to narrow down.'],

    ),

    ## will change it in api to [credit] ..
    'insufficient_credit' => array(
        600 => [401,'Insufficient credit or your account is inactive!']
    ),

    'not_found' => array(
        800 => [404,'No results were returned for your search, please try a different data.'],
        801 => [404,'No results were returned for your search, please try a different address.'],
        802 => [404,'No results were returned for your search, please try a different number.'],
        803 => [404,'No results were returned for your search. Please try a different username or add additional information.'],
        804 => [404,'No results were returned for your search, please try a different email.'],
        805 => [404,'Report does not exist.'],
        806 => [404,'Summary does not exist.'],
    ),

    'error' => array(
        500 => [500,'Unknown error has occurred'],
        429 => [429,'You have exceeded our rate limit'],
        900 => [400,'Missing output URL'],
        901 => [400,'Invalid Output URL'],
        902 => [400,'Missing output type'],
        903 => [400,'Not supported output type'],
        904 => [400,'Missing output destination'],
        905 => [400,'Missing output Email'],
        906 => [400,'Invalid output Email'],
        907 => [400,'Missing FTP details'],
        
        908 => [400,'Missing output'],
        
        909 => [400,'Missing FTP host'],
        911 => [400,'Missing FTP username'],
        912 => [400,'Missing FTP password'],
        913 => [400,'Invalid output destination'],
        914 => [400,'Invalid FTP host'],

    ),

    'api_error' => array(
        920 => [400,'Invalid '],
        921 => [400,'Missing '],
        922 => [400,'Empty Data '],
    )

);




