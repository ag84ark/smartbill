<?php

namespace Ag84ark\SmartBill\Tests\Endpoints;

use Ag84ark\SmartBill\Endpoints\BaseEndpoint;
use Ag84ark\SmartBill\Endpoints\EmailEndpoint;
use Ag84ark\SmartBill\Resources\SendEmail;
use Ag84ark\SmartBill\SmartBillCloudRestClient;
use Ag84ark\SmartBill\Tests\TestCase;
use Orchestra\Testbench\Concerns\WithWorkbench;

class EmailEndpointTest extends TestCase
{
    use WithWorkbench;
    private $restClient;
    private EmailEndpoint $emailEndpoint;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restClient = $this->createMock(SmartBillCloudRestClient::class);
        $this->emailEndpoint = new EmailEndpoint($this->restClient);
    }

    /** @test */
    public function it_sends_an_email_successfully()
    {
        $email = new SendEmail('test', 'test', 'factura');

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(
                SmartBillCloudRestClient::HTTP_POST,
                BaseEndpoint::EMAIL_URL,
                $email->toArray()
            )
            ->willReturn(true);

        $this->assertTrue($this->emailEndpoint->sendEmail($email));
    }

    /** @test */
    public function it_fails_to_send_an_email()
    {
        $email = new SendEmail('test', 'test', 'factura');

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(
                SmartBillCloudRestClient::HTTP_POST,
                BaseEndpoint::EMAIL_URL,
                $email->toArray()
            )
            ->willReturn(false);

        $this->assertFalse($this->emailEndpoint->sendEmail($email));
    }
}
