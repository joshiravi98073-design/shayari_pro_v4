CREATE DATABASE IF NOT EXISTS shayari_db
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE shayari_db;

CREATE TABLE IF NOT EXISTS shayaris (
  id INT AUTO_INCREMENT PRIMARY KEY,
  text TEXT NOT NULL,
  category VARCHAR(50) NOT NULL,
  tags VARCHAR(200),
  is_featured TINYINT(1) DEFAULT 0,
  likes INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(60) NOT NULL UNIQUE,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS ai_rate_limit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ip VARCHAR(45) NOT NULL,
  hits INT DEFAULT 1,
  window_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* ATTITUDE */
INSERT INTO shayaris (text, category, tags, is_featured) VALUES
('झुकते वो हैं जिनमें जान नहीं,\nहम वो हैं जिनकी पहचान नहीं।\nदुनिया पूछती है – कौन हो तुम?\nहम वो हैं जिनका कोई सानी नहीं।','ATTITUDE','attitude,power,self-respect,sher',1),
('हम कम बोलते हैं मगर सोचते गहरा हैं,\nजो समझ न सके वो बस देखते ही रह गया।\nभीड़ से अलग है हमारी चाल हमेशा,\nहम शेर हैं, भेड़ों में चलना हमने सीखा ही नहीं।','ATTITUDE','attitude,deep,thinking,sher',1),
('आवाज़ कम है पर असर बहुत है,\nसब्र कम है पर कहर बहुत है।\nहमसे टकराने से पहले सोच लेना,\nहमारी चुप्पी में भी ज़हर बहुत है।','ATTITUDE','dangerous,attitude,silence',0);

/* MOTIVATION */
INSERT INTO shayaris (text, category, tags, is_featured) VALUES
('अब पछतावे में नहीं जीता मैं,\nअतीत को सबक बना लिया है।\nजो लोग मुझे कमज़ोर समझते थे,\nआज उन्हें ही जवाब बना लिया है।','MOTIVATION','past,comeback,lesson',1),
('जो बीत गया उसे जाने दो,\nहर दर्द को अब मिट जाने दो।\nकल ने अगर साथ छोड़ा है,\nतो आज से नई कहानी शुरू होने दो।','MOTIVATION','new-start,life,change',1);

/* DOSTI_COLLEGE */
INSERT INTO shayaris (text, category, tags, is_featured) VALUES
('अकेले आए थे कॉलेज में,\nपर एक पूरा कारवाँ बन गया।\nये दोस्त नहीं, मेरी जान के टुकड़े हैं,\nजिनके बिना मैं अधूरा सा रह गया।','DOSTI_COLLEGE','college,friends,yaari',1),
('किताबें भूल जाएँगे एक दिन,\nlectures भी याद नहीं रहेंगे।\nपर तुम सबके साथ बिताए वो पागल पल,\nमरते दम तक दिल में ज़िंदा रहेंगे।','DOSTI_COLLEGE','memories,college-life,friends',1);

/* DARD */
INSERT INTO shayaris (text, category, tags, is_featured) VALUES
('वक़्त ने जब आईना दिखाया,\nतो असली चेहरे नज़र आए।\nजिन्हें हम अपना समझते थे,\nवही सबसे पहले बेगाने नज़र आए।','DARD','trust,faces,betrayal',1),
('इश्क़ में धोखा खाया हूँ, अब प्यार से डरता हूँ,\nदिल जितना टूटा है, अब दोबारा जुड़ने से डरता हूँ।\nलोग कहते हैं – सब एक जैसे नहीं होते,\nपर भरोसा अब अपने ही फैसलों पर नहीं होता।','DARD','love,breakup,fear',0);

/* SELF */
INSERT INTO shayaris (text, category, tags, is_featured) VALUES
('रख सको तो दिल की एक पहचान हूँ मैं,\nन समझो तो बस एक अनजान हूँ मैं।\nदर्द मिला तो भी मुस्कुरा लिया हमने,\nहर ग़म को दिल में छुपा लिया हमने।','SELF','identity,pain,smile',1),
('दुनिया पूछती है – तुम्हारी ताकत क्या है?\nमैं मुस्कुरा कर कहता हूँ – मेरी ज़िम्मेदारी।\nजो भी मेरे साथ होता है, उसकी कमान मेरे हाथ,\nहार हो या जीत, दोनों की मालकियत मेरी है।','SELF','responsibility,power,ownership',0);
