# phluxor-example

php actor model toolkit [phluxor](https://github.com/ytake/phluxor) example.  

classroom / teacher / student actors example.

```mermaid
flowchart TD
subgraph ActorSystem
教室アクター --> |PrepareTest|先生アクター
先生アクター --> |StartTest|生徒アクター
生徒アクター --> |SubmitTest|先生アクター
end
```

```mermaid
flowchart TD
subgraph ActorSystem
Teacher("先生アクター") --> |生成|student-1
Teacher("先生アクター") --> |生成|student-2
Teacher("先生アクター") --> |生成|student-3
Teacher("先生アクター") --> |生成|student-4
Teacher("先生アクター") --> |生成|student-5
end
```

最後にPoisonPillでアクターが終了します。  
finally, actor is terminated by PoisonPill.

