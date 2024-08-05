# phluxor-example

php actor model toolkit [phluxor](https://github.com/ytake/phluxor) example.  

ReentrantActorを使って、アクターが再帰的にメッセージを送信する例です。

futureを用いてアクター間でメッセージ送信を行いますが、  
タイムアウト時にデッドレターを送信しながら、次々とメッセージを送信します。

This is an example of sending messages recursively using ReentrantActor.

Use future to send messages between actors,  
send dead letters on timeout, and send messages one after another.  
