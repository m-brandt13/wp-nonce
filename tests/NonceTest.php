<?php
namespace mbrandt13\WordPressNonce\tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit_Framework_TestCase;
use mbrandt13\WordPressNonce\Nonce;

class NonceTest extends PHPUnit_Framework_TestCase
{

    protected $action;

    protected $name;

    protected $mock_value;

    protected $url;

    protected $mock_url;

    protected $mock_field;

    protected $mock_referrer_field;

    protected $mock_message;

    public function setUp() {
        parent::setUp();
        Monkey\setUp();

        $this->action = 'action';
        $this->name = 'name';
        $this->mock_value = 'mock_value';
        $this->url = 'http://www.company.invalid/';
        $this->mock_url = $this->url . '?' . $this->name . '=' . $this->mock_value;
        $this->mock_field = '<input type="hidden" name="' . $this->name . '" value="' . $this->mock_value . '">';
        $this->mock_referrer_field = '<input type="hidden" name="_wp_http_referer" value="'. $this->url . '" />';
        $this->mock_message = 'Are you sure to do this?';
    }

    public function tearDown()
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    public function test_create_nonce() {
        $nonce = new Nonce( $this->action, $this->name );

        $this->assertNotNull( $nonce );
    }

    public function test_get_value() {
        Functions\expect( 'wp_create_nonce' )
            ->once()
            ->with( $this->action )
            ->andReturn( $this->mock_value );
        $nonce = new Nonce( $this->action, $this->name );

        $this->assertEquals( $nonce->get_value(), $this->mock_value );
    }

    public function test_get_url() {
        Functions\expect( 'wp_nonce_url' )
            ->once()
            ->with( $this->url, $this->action, $this->name )
            ->andReturn( $this->mock_url );
        $nonce = new Nonce( $this->action, $this->name );
        $this->assertEquals( $nonce->get_url( $this->url ), $this->mock_url );
    }

    public function test_get_field() {
        if (!empty($this->mock_field)) {
            Functions\expect( 'wp_nonce_field' )
                ->once()
                ->with( $this->action, $this->name, FALSE, FALSE )
                ->andReturn( $this->mock_field );
        }

        $nonce = new Nonce( $this->action, $this->name );
        $this->assertEquals( $nonce->get_field( FALSE, FALSE ), $this->mock_field );
    }

    public function test_get_referrer_field() {
        Functions\expect( 'wp_referrer_field' )
            ->once()
            ->with( FALSE )
            ->andReturn( $this->mock_referrer_field );

        $nonce = new Nonce( $this->action, $this->name );
        $this->assertEquals( $nonce->get_field( FALSE ), $this->mock_referrer_field );
    }

    public function test_verify_admin() {
        Functions\expect( 'check_admin_referrer' )
            ->once()
            ->with( $this->action, $this->name )
            ->andReturn( TRUE );

        $nonce = new Nonce( $this->action, $this->name );
        $this->assertTrue( $nonce->verify_admin() );
    }

    public function test_verify_ajax() {
        Functions\expect( 'check_ajax_referrer' )
            ->once()
            ->with( $this->action, $this->name, TRUE )
            ->andReturn( TRUE );

        $nonce = new Nonce( $this->action, $this->name );
        $this->assertTrue( $nonce->verify_ajax( TRUE ) );
    }

    public function test_verify_() {
        Functions\expect( 'wp_verify_nonce' )
            ->once()
            ->with( $this->mock_value, $this->action )
            ->andReturn( TRUE );

        $nonce = new Nonce( $this->action, $this->name );
        $this->assertTrue( $nonce->verify( $this->mock_value ) );
    }

    public function test_ays() {
        Functions\expect( 'wp_nonce_ays' )
            ->once()
            ->with( $this->action )
            ->andReturnUsing( function() {
                echo $this->mock_message;
            } );
        $nonce = new Nonce( $this->action, $this->name );

        $this->expectOutputString( $this->mock_message );
        $this->assertNull( $nonce->ays() );
    }

}
