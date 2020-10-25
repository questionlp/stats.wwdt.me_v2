CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `ww_guestscores` AS 
  select 
    `s`.`showdate` AS `showdate`,
    `gm`.`showguestmapid` AS `showguestmapid`,
    `g`.`guestid` AS `guestid`,
    `g`.`guest` AS `guest`,
    `gm`.`guestscore` AS `guestscore` 
  from 
    ((`ww_shows` `s` join `ww_showguestmap` `gm` on((`s`.`showid` = `gm`.`showid`))) join `ww_guests` `g` on((`g`.`guestid` = `gm`.`guestid`))) 
  order by 
    `s`.`showdate`,`gm`.`showguestmapid`;


CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `ww_panelistscores` AS 
  select 
    `s`.`showdate` AS `showdate`,
    `pm`.`showpnlmapid` AS `showpnlmapid`,
    `p`.`panelist` AS `panelist`,
    `pm`.`panelistscore` AS `panelistscore` 
  from 
    ((`ww_shows` `s` join `ww_showpnlmap` `pm` on((`s`.`showid` = `pm`.`showid`))) join `ww_panelists` `p` on((`pm`.`panelistid` = `p`.`panelistid`))) 
  order by 
    `s`.`showdate`,`pm`.`showpnlmapid`;


CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `ww_showdetails` AS 
  select 
    `s`.`showdate` AS `showdate`,
    `h`.`host` AS `host`,
    `sc`.`scorekeeper` AS `scorekeeper`,
    `g`.`guest` AS `guest` 
  from 
    ((((`ww_shows` `s` join `ww_hosts` `h` on((`s`.`hostid` = `h`.`hostid`))) join `ww_scorekeepers` `sc` on((`sc`.`scorekeeperid` = `s`.`scorekeeperid`))) join `ww_showguestmap` `sg` on((`sg`.`showid` = `s`.`showid`))) join `ww_guests` `g` on((`sg`.`guestid` = `g`.`guestid`))) WITH CASCADED CHECK OPTION;


CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `ww_showfulldetails` AS 
  select 
    `s`.`showid` AS `showid`,
    `s`.`showdate` AS `showdate`,
    `sd`.`showdescription` AS `showdescription`,
    `s`.`bestof` AS `bestof`,
    `h`.`host` AS `host`,
    `sc`.`scorekeeper` AS `scorekeeper`,
    `pm`.`showpnlmapid` AS `showpnlmapid`,
    `p`.`panelist` AS `panelist`,
    `pm`.`panelistscore` AS `panelistscore`,
    `gm`.`showguestmapid` AS `showguestmapid`,
    `g`.`guest` AS `guest`,
    `gm`.`guestscore` AS `guestscore` 
  from 
    (((((((`ww_shows` `s` join `ww_showpnlmap` `pm` on((`s`.`showid` = `pm`.`showid`))) join `ww_panelists` `p` on((`pm`.`panelistid` = `p`.`panelistid`))) join `ww_showguestmap` `gm` on((`s`.`showid` = `gm`.`showid`))) join `ww_guests` `g` on((`gm`.`guestid` = `g`.`guestid`))) join `ww_hosts` `h` on((`s`.`hostid` = `h`.`hostid`))) join `ww_scorekeepers` `sc` on((`s`.`scorekeeperid` = `sc`.`scorekeeperid`))) join `ww_showdescriptions` `sd` on((`s`.`showid` = `sd`.`showid`))) 
  order by 
    `s`.`showdate`,`pm`.`showpnlmapid`,`pm`.`panelistscore`;


CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `ww_showfulldetails_withids` AS 
  select 
    `s`.`showid` AS `showid`,
    `s`.`showdate` AS `showdate`,
    `sd`.`showdescription` AS `showdescription`,
    `s`.`bestof` AS `bestof`,
    `h`.`host` AS `host`,
    `sc`.`scorekeeper` AS `scorekeeper`,
    `pm`.`showpnlmapid` AS `showpnlmapid`,
    `p`.`panelistid` AS `panelistid`,
    `p`.`panelist` AS `panelist`,
    `pm`.`panelistscore` AS `panelistscore`,
    `gm`.`showguestmapid` AS `showguestmapid`,
    `g`.`guestid` AS `guestid`,
    `gm`.`guestscore` AS `guestscore` 
  from 
    (((((((`ww_shows` `s` join `ww_showpnlmap` `pm` on((`s`.`showid` = `pm`.`showid`))) join `ww_panelists` `p` on((`pm`.`panelistid` = `p`.`panelistid`))) join `ww_showguestmap` `gm` on((`s`.`showid` = `gm`.`showid`))) join `ww_guests` `g` on((`gm`.`guestid` = `g`.`guestid`))) join `ww_hosts` `h` on((`s`.`hostid` = `h`.`hostid`))) join `ww_scorekeepers` `sc` on((`s`.`scorekeeperid` = `sc`.`scorekeeperid`))) join `ww_showdescriptions` `sd` on((`s`.`showid` = `sd`.`showid`))) 
  order by 
    `s`.`showdate`,`pm`.`showpnlmapid`,`pm`.`panelistscore`;
