#DROP VIEW IF EXISTS leaderboard;
CREATE VIEW leaderboard AS
    SELECT cm.company_id,
           r.campaign_id,
           r.send_date,
           rs.first_name,
           rs.last_name,
           IF(r.email != '', r.email, rs.email) AS email,
           IF(r.phone != '', r.phone, rs.mobile) AS phone,
           r.recipient_id,
           COUNT(r1.type_id) AS mails_sent,
           COUNT(r4.type_id) AS reported_phishes,
           COUNT(r3.type_id) AS phished,
           ROUND(COUNT(r3.type_id) * 100 / COUNT(r1.type_id), 0) AS phish_rate,
           ROUND(COUNT(r4.type_id) * 100 / COUNT(r1.type_id), 0) AS reporting_rate,
           COUNT(r11.type_id) AS sms_sent,
           COUNT(r6.type_id) AS smished,
           ROUND(COUNT(r6.type_id) * 100 / COUNT(r11.type_id), 0) AS smish_rate,
           rs.department,
           rs.location
    FROM results r
        LEFT JOIN recipients rs ON rs.id = r.recipient_id
        LEFT JOIN campaigns cm ON r.campaign_id = cm.id
        LEFT JOIN results r1 ON r1.id = r.id
                                    AND r1.type_id = 1
                                    AND r1.email IS NOT NULL
                                    AND (r1.phone IS NULL OR r1.phone = '')
        LEFT JOIN results r11 ON r11.id = r.id
                                     AND r11.type_id = 1
                                     AND r11.phone IS NOT NULL
                                     AND (r11.email IS NULL OR r11.email = '')
        LEFT JOIN results r3 ON r3.id = r.id AND r3.type_id = 3
        LEFT JOIN results r4 ON r4.id = r.id AND r4.type_id = 4
        LEFT JOIN results r6 ON r6.id = r.id AND r6.type_id = 6
    GROUP BY r.recipient_id;



