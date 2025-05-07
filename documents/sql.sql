select * from stations
  WHERE competitionId = 1;

select * from users
  WHERE roleId = 2;

(select supervisor.name, supervisor.id from (select * from users
  WHERE roleId = 2) as supervisor
  left join (select * from stations
  WHERE competitionId = 1) as station on supervisor.id = station.userId
  WHERE station.userId is null
ORder BY supervisor.name)
union 
  select u.name, u.id from users u
  inner join stations s on u.id = s.userId
  where s.id = 1
;

update competitions set currentComp = 0;

select * from competitions;

select id, name from competitions
  where currentComp = 1;

select s.id, s.name, s.location, s.weighting, s.moreIsBetter, s.typeId, s.userId, s.competitionId, c.name as competitionName from competitions c
  inner join stations s on c.id = s.competitionId
  where c.currentComp = 1;


-- CsapatAz¡llom·son teamAtStation
SELECT ts.id, t.name, ts.stationId, t.id teamId FROM team_at_stations ts
  INNER JOIN teams t ON ts.teamId = t.id
  WHERE ts.stationId = 2;

-- tagokEredmÈnyeiAz¡llom·son membersResultsAtStation
SELECT m.id, m.teamAtStationId, m.teamMemberId, t.name, m.result, m.resultTime FROM member_results_at_stations m 
  INNER JOIN team_members t ON t.id = m.teamMemberId
  WHERE m.teamAtStationId = 12 AND t.teamId = 10
ORDER BY t.name;

SELECT * FROM teams t 
  INNER JOIN team_members m ON t.id = m.teamId
ORDER BY t.name, m.name;




