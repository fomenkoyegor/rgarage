create table `users`
(
    id       int unsigned not null primary key auto_increment,
    name     varchar(255) not null,
    email    varchar(255) not null unique,
    password varchar(255) not null
);
describe users;

insert into users(name, email, password)
values ('re', 're@re.re', 'rerere');
insert into users(name, email, password)
values ('we', 'we@we.we', 'wewewe');

select *
from users;

create table `projects`
(
    id      int unsigned not null primary key auto_increment,
    name    varchar(255) not null,
    user_id int unsigned not null,
    foreign key (user_id) references users (id) on delete cascade on update restrict
);

insert into projects(name, user_id)
values ('гараж', 1);

select *
from projects
where user_id = 1;


create table `tasks`
(
    id         int unsigned not null primary key auto_increment,
    name       varchar(255) not null,
    priority   int          null     default null,
    user_id    int unsigned not null,
    status     tinyint      not null default 0,
    project_id int unsigned null     default null,
    date       date         null     default null,
    foreign key (user_id) references users (id) on delete cascade on update restrict,
    foreign key (project_id) references projects (id) on delete cascade on update restrict
);

insert into tasks(name, project_id, user_id)
values ('task1', 1, 1);

insert into tasks(name, user_id)
values ('milk', 1);



SELECT *,
       (SELECT COUNT(*) FROM tasks WHERE tasks.project_id = projects.id) as count_tasks
FROM projects
ORDER BY `count_tasks` DESC;

select status, name
FROM tasks
where status = true
ORDER BY `name`;


SELECT *,
       (SELECT COUNT(*) FROM tasks WHERE tasks.project_id = projects.id) as count_tasks
FROM projects
ORDER BY `name`;


SELECT *
FROM `tasks`
WHERE name LIKE 'N%';


SELECT name
FROM `tasks`
GROUP BY name
HAVING COUNT(*) > 1;


SELECT name, status
FROM `tasks`
GROUP BY name, status
HAVING COUNT(*) > 1;


SELECT *,
       (SELECT COUNT(*) FROM tasks WHERE tasks.project_id = projects.id AND tasks.status = true) as tasks_complite
FROM projects
HAVING `tasks_complite` > 10
ORDER BY `tasks_complite` DESC;
