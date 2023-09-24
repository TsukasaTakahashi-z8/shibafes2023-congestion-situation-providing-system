# DB系

以下の3つのテーブルから構成されます。

- visitor
- exhibition
- path

## visitor テーブル

| カラム名 | データ型 | 備考 |
| --- | --- | --- |
| uid | INT | PK, auto increment |
| status | INT | NOT NULL 0:未使用, 1:退場中,2: 入場中 |

```sql
CREATE TABLE IF NOT EXISTS visitor (
    uid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    status INT NOT NULL CHECK (0 <= status AND status <= 2)
);
```

## exhibition テーブル

| カラム名 | データ型 | 備考 |
| --- | --- | --- |
| id | INT | 企画ID |
| category | VARCHAR | 出展区分 |
| title | VARCHAR | 企画名 |
| club_name | VARCHAR | 出展団体名 |

```sql
CREATE TABLE IF NOT EXISTS exhibition (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    category VARCHAR(10) NOT NULL,
    title VARCHAR(255) NOT NULL,
    club_name VARCHAR(255) NOT NULL
);
```

## path テーブル

| カラム名 | データ型 | 備考 |
| --- | --- | --- |
| path_id | INT | PK, auto increment |
| uid | INT | NOT NULL |
| exhibition_id | INT | NOT NULL |
| datetime | DATETIME | NOT NULL, CURRENT TIME STAMP |
| flag | INT | NOT NULL, 0:指定なし, 1:退場指定, 2:入場指定 |

```sql
CREATE TABLE IF NOT EXISTS path (
    path_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    uid INT NOT NULL,
    exhibition_id INT NOT NULL,
    datetime DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3),
    flag INT NOT NULL CHECK (0 <= flag AND flag <= 2)
);
```
