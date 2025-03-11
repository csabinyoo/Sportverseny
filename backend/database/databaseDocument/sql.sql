SELECT s.name, s.typeId FROM stations s
  INNER JOIN team_at_stations t ON s.id = t.stationId
  WHERE t.id = 1;

select * FROM member_results_at_stations
  order by teamAtStationId, teamMemberId;