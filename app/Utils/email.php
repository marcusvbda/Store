<?php
namespace App\Utils;
class Email
{
    public static function send($to,$subject,$body,$info)
    {
        try
        {
            config(['mail.from.adress'=> $info["mail"] ]);
            config(['mail.from.name'  => $info["name"] ]);
            config(['mail.driver'     => $info["driver"] ]);
            config(['mail.host'       => $info["smtp"] ]);
            config(['mail.port'       => $info["port"] ]);
            config(['mail.username'   => $info["mail"]  ]);
            config(['mail.password'   => $info["password"] ]);
            config(['mail.encryption' => $info["encryption"] ]);


            \Mail::send([], [], function ($message) use ($to,$subject,$body)
            {
                $message->to($to)
                    ->subject($subject)
                    ->setBody($body, 'text/html'); 
            });
            return ["code"=>202,"success"=>true,"message" => "Email enviado com sucesso !!"];
        }
        catch (\Exception $e) 
        {
            return ["code"=>505,"success"=>false,"message" => $e->getMessage()];
        }
    }

}