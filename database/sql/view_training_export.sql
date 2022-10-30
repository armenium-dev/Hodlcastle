#DROP VIEW IF EXISTS training_export;
CREATE VIEW training_export AS
SELECT
    m.id AS m_id,
    m.name AS m_name,
    m.public,
    r.first_name,
    r.last_name,
    r.email,
    c.name AS c_name,
    ts.id AS ts_id,
    ts.recipient_id,
    ts.company_id,
    ts.code,
    ts.start_training,
    ts.finish_training,
    ts.is_finish,
    TIMESTAMPDIFF(MINUTE, ts.start_training, ts.finish_training) AS timespend
FROM training_statistics ts
LEFT JOIN companies c on c.id = ts.company_id
LEFT JOIN recipient_training rt on ts.recipient_id = rt.recipient_id
LEFT JOIN trainings t on rt.training_id = t.id
LEFT JOIN modules m on t.module_id = m.id
LEFT JOIN recipients r on ts.recipient_id = r.id
WHERE ts.deleted_at IS NULL
GROUP BY ts.id;
