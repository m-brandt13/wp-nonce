# wp-nonce
 # wp-nonce

Use WordPress Nonces in an object oriented context.

Install using composer

The composer.json file
```
    {
        "repositories" : 
        [
            {
                "type" : "vcs",
                "url" : "htpps://github.com/m-brandt13/wp-nonce"
            }
    
        ]
    
        "require" : 
        {
            "m-brandt13/wp-nonce" : "1.0.*"
        }
 
    }
```

 use mbrandt13\WordPressNonce\nNonce
 ```
 Create nonce
 
    $nonce = new Nonce( $this->action, $this->name );

//Get nonce url
	$url = 'https://www.tests.de' // URL to wich nonce is added
	$nonce_url = $nonce -<get_url($url)

//Get nonce value
	
	$value = $nonce->get_value();

//Get nonce field
	$referrer = TRUE; // get referrer field in addition to nonce field
	$echo = TRUE; // echo field instead of returning it
	$nonce->get_field( $referrer, $echo );

// Get referrer field
	$echo = TRUE; // echo field instead of returning it
	$nonce->get_referrer_field( $echo );

// Verify admin
	$nonce->verify_admin();

// Verify AJAX
	$die = TRUE // die on failure instead of returning FALSE
	$nonce->verify_ajax( $die );

// Verify nonce
	$value; // must contain the nonce value;
	$nonce->verify( $value );

//Show AYS mesage
	$nonce->ays();   

```     
