USE shayari_db;

-- poet columns add karo agar nahi hain
ALTER TABLE shayaris ADD COLUMN IF NOT EXISTS poet VARCHAR(100) DEFAULT NULL;
ALTER TABLE shayaris ADD COLUMN IF NOT EXISTS poet_era VARCHAR(50) DEFAULT NULL;

/* ═══════════════════════════════════════
   MIRZA GHALIB — Real Shayari
   ये unki famous asli shayari hain
═══════════════════════════════════════ */
INSERT INTO shayaris (text, category, tags, is_featured, poet, poet_era) VALUES

('हज़ारों ख़्वाहिशें ऐसी कि हर ख़्वाहिश पे दम निकले,\nबहुत निकले मेरे अरमान, लेकिन फिर भी कम निकले।',
'GHALIB','ghalib,khwahish,armaan',1,'Mirza Ghalib','1797–1869'),

('दिल-ए-नादाँ तुझे हुआ क्या है,\nआख़िर इस दर्द की दवा क्या है।\nहम हैं मुश्ताक़ और वो बेज़ार,\nया इलाही! ये माजरा क्या है।',
'GHALIB','ghalib,dil,dard,naadan',1,'Mirza Ghalib','1797–1869'),

('इश्क़ पर ज़ोर नहीं, है ये वो आतिश ग़ालिब,\nकि लगाए न लगे और बुझाए न बुझे।',
'GHALIB','ghalib,ishq,aatish,junoon',1,'Mirza Ghalib','1797–1869'),

('मोहब्बत में नहीं है फ़र्क़ जीने और मरने का,\nउसी को देख कर जीते हैं जिस काफ़िर पे दम निकले।',
'GHALIB','ghalib,mohabbat,jeena,marna',1,'Mirza Ghalib','1797–1869'),

('आह को चाहिए इक उम्र असर होने तक,\nकौन जीता है तेरी ज़ुल्फ़ के सर होने तक।',
'GHALIB','ghalib,aah,umr,zulf',1,'Mirza Ghalib','1797–1869'),

('रगों में दौड़ते फिरने के हम नहीं क़ायल,\nजब आँख ही से न टपका तो फिर लहू क्या है।',
'GHALIB','ghalib,aansu,lahoo,dard',1,'Mirza Ghalib','1797–1869'),

('ये न थी हमारी क़िस्मत कि विसाल-ए-यार होता,\nअगर और जीते रहते यही इंतज़ार होता।',
'GHALIB','ghalib,qismat,visaal,intezaar',1,'Mirza Ghalib','1797–1869'),

('ग़म-ए-हस्ती का असद किससे हो जुज़ मर्ग इलाज,\nशमा हर रंग में जलती है सहर होने तक।',
'GHALIB','ghalib,hasti,marg,shama',0,'Mirza Ghalib','1797–1869'),

('न था कुछ तो ख़ुदा था, कुछ न होता तो ख़ुदा होता,\nडुबोया मुझको होने ने, न होता मैं तो क्या होता।',
'GHALIB','ghalib,khuda,wajood,falsafa',1,'Mirza Ghalib','1797–1869'),

('बाज़ीचा-ए-अतफ़ाल है दुनिया मेरे आगे,\nहोता है शब-ओ-रोज़ तमाशा मेरे आगे।',
'GHALIB','ghalib,duniya,tamasha,zindagi',0,'Mirza Ghalib','1797–1869');

/* ═══════════════════════════════════════
   JOHN ELIA — Real Shayari
   Bebaak, dard bhari, seedhi baat
═══════════════════════════════════════ */
INSERT INTO shayaris (text, category, tags, is_featured, poet, poet_era) VALUES

('मैं भी बहुत अजीब हूँ, इतना अजीब हूँ,\nतुझे भुला के भी मैं तुझसे ही डरता हूँ।',
'JOHN_ELIA','john elia,ajeeb,bhulana,dar',1,'John Elia','1931–2002'),

('तुम बिल्कुल हम जैसे निकले,\nअब तक कहाँ छुपे थे भाई।',
'JOHN_ELIA','john elia,milna,apnapan',1,'John Elia','1931–2002'),

('हम तो डूबे हैं सनम, तुमको भी ले डूबेंगे,\nजाने क्या सोच के तुम हम से मिलने आए थे।',
'JOHN_ELIA','john elia,doobna,ishq,junoon',1,'John Elia','1931–2002'),

('वो जो शायर था, बर्बाद हो गया,\nऔर बर्बाद हो के आबाद हो गया।',
'JOHN_ELIA','john elia,shayar,barbaad,aabad',1,'John Elia','1931–2002'),

('मुझे ख़ुद अपने आप से हुई है बदगुमानी,\nमैं हर तरफ़ से हारा हूँ, हर तरफ़ से थका हूँ।',
'JOHN_ELIA','john elia,haar,thakan,khud',1,'John Elia','1931–2002'),

('ये शाम भी अजीब है, ये रात भी अजीब,\nकुछ टूटने की आवाज़ें आती हैं क़रीब।',
'JOHN_ELIA','john elia,shaam,raat,tootna',1,'John Elia','1931–2002'),

('क्या करूँ, कहाँ जाऊँ, किसे पुकारूँ मैं,\nइस दुनिया में कोई मेरा नहीं लगता।',
'JOHN_ELIA','john elia,akela,duniya,dard',1,'John Elia','1931–2002'),

('मैंने सोचा न था कि ज़िंदगी इस तरह,\nआँख मूँद के गुज़र जाएगी चुपचाप।',
'JOHN_ELIA','john elia,zindagi,guzarna,chup',1,'John Elia','1931–2002'),

('जो हो न सका वो अब भी मेरे ख़्वाबों में है,\nये ज़िंदगी मेरे सपनों की क़ातिल है।',
'JOHN_ELIA','john elia,khwab,zindagi,qatil',1,'John Elia','1931–2002');

/* ═══════════════════════════════════════
   FAIZ AHMED FAIZ — Real Shayari
   Ishq + Inquilab dono saath
═══════════════════════════════════════ */
INSERT INTO shayaris (text, category, tags, is_featured, poet, poet_era) VALUES

('मुझसे पहली-सी मोहब्बत मेरी महबूब न माँग,\nमैंने समझा था कि तू है तो दरख्शाँ है हयात।\nतेरा ग़म है तो ग़म-ए-दहर का झगड़ा क्या है,\nअब भी दिलकश है तेरा हुस्न मगर क्या कहिए।',
'FAIZ','faiz,mohabbat,mahboob,hayat',1,'Faiz Ahmed Faiz','1911–1984'),

('और भी दुःख हैं ज़माने में मोहब्बत के सिवा,\nराहतें और भी हैं वस्ल की राहत के सिवा।',
'FAIZ','faiz,dukh,mohabbat,rahat',1,'Faiz Ahmed Faiz','1911–1984'),

('बोल कि लब आज़ाद हैं तेरे,\nबोल ज़बाँ अब तक तेरी है।\nबोल कि सच ज़िंदा है अब तक,\nबोल जो कुछ कहना है कह दे।',
'FAIZ','faiz,bol,azaad,sach,zuban',1,'Faiz Ahmed Faiz','1911–1984'),

('हम देखेंगे, लाज़िम है कि हम भी देखेंगे,\nवो दिन कि जिसका वादा है जो लौह-ए-अज़ल में लिखा है।',
'FAIZ','faiz,hum dekhenge,inqilab,vaada',1,'Faiz Ahmed Faiz','1911–1984'),

('गुलों में रंग भरे, बाद-ए-नौ-बहार चले,\nचले भी आओ कि गुलशन का कारोबार चले।',
'FAIZ','faiz,gulon,bahar,gulshan',1,'Faiz Ahmed Faiz','1911–1984'),

('निसार मैं तेरी गलियों के ऐ वतन, कि जहाँ\nचली है रस्म कि कोई न सर उठा के चले।',
'FAIZ','faiz,vatan,watan,nagar',1,'Faiz Ahmed Faiz','1911–1984'),

('आज बाज़ार में पा-ब-जौलाँ चलो,\nदस्त-अफ़शाँ चलो, मस्त-ओ-रक़्साँ चलो।\nख़ाक-बर-सर चलो, ख़ूँ-ब-दामाँ चलो,\nअपना दिल थाम लो, जोश-ए-दिल थाम लो।',
'FAIZ','faiz,bazaar,chalo,josh',1,'Faiz Ahmed Faiz','1911–1984');

/* ═══════════════════════════════════════
   RAHAT INDORI — Real Shayari
   Awaam ki baat, seedha andaaz
═══════════════════════════════════════ */
INSERT INTO shayaris (text, category, tags, is_featured, poet, poet_era) VALUES

('सभी का ख़ून है शामिल यहाँ की मिट्टी में,\nकिसी के बाप का हिंदुस्तान थोड़ी है।',
'RAHAT','rahat indori,hindustan,mitti,khoon',1,'Rahat Indori','1950–2020'),

('लगेगी आग तो आएँगे घर कई ज़द में,\nयहाँ पे सिर्फ़ हमारा मकान थोड़ी है।',
'RAHAT','rahat indori,aag,ghar,makaan',1,'Rahat Indori','1950–2020'),

('बुला रहा है मुझे आसमान, उड़ने दो,\nये पाँव ज़मीन में गड़े हैं, उखड़ने दो।',
'RAHAT','rahat indori,aasman,udna,zameen',1,'Rahat Indori','1950–2020'),

('घर से मस्जिद है बहुत दूर, चलो यूँ कर लें,\nकिसी रोते हुए बच्चे को हँसाया जाए।',
'RAHAT','rahat indori,insaaniyat,mazhab,baccha',1,'Rahat Indori','1950–2020'),

('मैं भी दीवाना, तू भी दीवाना,\nफिर ये दुनिया क्यों नहीं दीवानी।',
'RAHAT','rahat indori,deewana,duniya,ishq',1,'Rahat Indori','1950–2020'),

('कोई हाथ भी न मिलाएगा, जो गले मिलोगे तपाक से,\nये नए मिज़ाज का शहर है, ज़रा फ़ासले से मिला करो।',
'RAHAT','rahat indori,sheher,fasla,dosti',1,'Rahat Indori','1950–2020'),

('मेरे होने पे शक है तो होने न दो,\nमुझे अपनी ख़बर है जानने न दो।',
'RAHAT','rahat indori,hona,shak,khabar',1,'Rahat Indori','1950–2020'),

('जो लोग पत्थर फेंकते हैं मेरे घर की तरफ़,\nउन्हें बता दो कि नींव में उनका भी घर है।',
'RAHAT','rahat indori,patthar,ghar,neev',1,'Rahat Indori','1950–2020');

/* ═══════════════════════════════════════
   GULZAR — Real Shayari
   Zindagi, mohabbat, cinema
═══════════════════════════════════════ */
INSERT INTO shayaris (text, category, tags, is_featured, poet, poet_era) VALUES

('कभी किसी को मुकम्मल जहाँ नहीं मिलता,\nकहीं ज़मीन तो कहीं आसमाँ नहीं मिलता।',
'GULZAR','gulzar,mukammal,zameen,aasman',1,'Gulzar','1934–'),

('तुमको देखा तो ये ख़्याल आया,\nज़िंदगी धूप तुम घना साया।',
'GULZAR','gulzar,dhoop,saaya,pyaar',1,'Gulzar','1934–'),

('नाम गुम जाएगा, चेहरा ये बदल जाएगा,\nमेरी आवाज़ ही पहचान है, गर याद रहे।',
'GULZAR','gulzar,naam,awaaz,pehchaan',1,'Gulzar','1934–'),

('मोड़ पे रुक के मुड़ के देखा है,\nज़िंदगी तू कहाँ खड़ी थी अभी।',
'GULZAR','gulzar,zindagi,mod,rukna',1,'Gulzar','1934–'),

('दिल ढूँढता है फिर वही, फुरसत के रात-दिन,\nबैठे रहें तसव्वुर-ए-जानाँ किए हुए।',
'GULZAR','gulzar,dil,fursat,tasavvur',1,'Gulzar','1934–'),

('कुछ और ज़माना कहता है, कुछ और होना चाहता है मन,\nइस दौर में बड़ा मुश्किल है, अपने जैसा होना।',
'GULZAR','gulzar,zamana,mann,mushkil',1,'Gulzar','1934–'),

('रात भर जागते हैं अपनी तरह,\nख़्वाब भी अपने तरह के देखते हैं।',
'GULZAR','gulzar,raat,jaagna,khwab',1,'Gulzar','1934–');

/* ═══════════════════════════════════════
   ALLAMA IQBAL — Real Shayari
   Khudi, qaum, parwaz
═══════════════════════════════════════ */
INSERT INTO shayaris (text, category, tags, is_featured, poet, poet_era) VALUES

('ख़ुदी को कर बुलंद इतना कि हर तक़दीर से पहले,\nख़ुदा बंदे से ख़ुद पूछे बता तेरी रज़ा क्या है।',
'IQBAL','iqbal,khudi,taqdeer,khuda',1,'Allama Iqbal','1877–1938'),

('तू शाहीन है, परवाज़ है काम तेरा,\nतेरे सामने आसमाँ और भी हैं।',
'IQBAL','iqbal,shaheen,parwaaz,aasman',1,'Allama Iqbal','1877–1938'),

('सितारों से आगे जहाँ और भी हैं,\nअभी इश्क़ के इम्तिहाँ और भी हैं।\nतही ज़िंदगी से नहीं यह फ़ज़ाएँ,\nयहाँ सैकड़ों कारवाँ और भी हैं।',
'IQBAL','iqbal,sitare,jahan,ishq,karvan',1,'Allama Iqbal','1877–1938'),

('नहीं तेरा नशेमन क़स्र-ए-सुल्तानी के गुंबद पर,\nतू शाहीन है, बसेरा कर पहाड़ों की चट्टानों में।',
'IQBAL','iqbal,nasheman,shaheen,pahad',1,'Allama Iqbal','1877–1938'),

('पत्थर की मूरतों में समझा है तू ख़ुदा है,\nख़ाकِ वतन का मुझको हर ज़र्रा देवता है।',
'IQBAL','iqbal,vatan,khuda,watan,devta',1,'Allama Iqbal','1877–1938'),

('ज़माने के अंदाज़ बदलते नहीं,\nये फ़ितरत बदलती है इंसाँ की।\nनई बात कहने के क़ाबिल बनो,\nकहानी वही है, नई है ज़बाँ की।',
'IQBAL','iqbal,zamana,fitrat,insaan,naya',1,'Allama Iqbal','1877–1938');

/* ═══════════════════════════════════════
   SAHIR LUDHIANVI — Real Shayari
   Films, ishq, dard
═══════════════════════════════════════ */
INSERT INTO shayaris (text, category, tags, is_featured, poet, poet_era) VALUES

('मैं पल दो पल का शायर हूँ,\nपल दो पल मेरी जवानी है।\nपल दो पल मेरी हस्ती है,\nपल दो पल मेरी कहानी है।',
'SAHIR','sahir,shayar,jawani,hasti,kahani',1,'Sahir Ludhianvi','1921–1980'),

('चलो एक बार फिर से अजनबी बन जाएँ हम दोनों,\nन मैं तुमसे कोई उम्मीद रखूँ,\nन तुम मेरी तरफ़ देखो।',
'SAHIR','sahir,ajnabi,ummeed,judai',1,'Sahir Ludhianvi','1921–1980'),

('जिन्हें नाज़ है हिंद पर वो कहाँ हैं,\nकहाँ हैं, कहाँ हैं, कहाँ हैं।',
'SAHIR','sahir,hind,naaz,kahan',1,'Sahir Ludhianvi','1921–1980'),

('वो सुबह कभी तो आएगी,\nजब होगी दुनिया सुहानी।\nजब मिट जाएँगी सब बाधाएँ,\nहर इंसाँ को मिलेगी रोटी।',
'SAHIR','sahir,subah,duniya,roti,umeed',1,'Sahir Ludhianvi','1921–1980'),

('तुम्हें अपना बना के मैंने,\nखोया है ख़ुद को पाया कहाँ।\nदिया दिल दिया चैन दिया सब,\nपर तुमने मुझे अपनाया कहाँ।',
'SAHIR','sahir,pyaar,khona,paana,dil',1,'Sahir Ludhianvi','1921–1980');

/* ═══════════════════════════════════════
   BASHIR BADR — Real Shayari
   Pyar, dard, roz ki zindagi
═══════════════════════════════════════ */
INSERT INTO shayaris (text, category, tags, is_featured, poet, poet_era) VALUES

('कुछ तो मजबूरियाँ रही होंगी,\nयूँ कोई बेवफ़ा नहीं होता।',
'BASHIR_BADR','bashir badr,majboori,bewafa',1,'Bashir Badr','1935–'),

('लोग टूट जाते हैं एक घर बनाने में,\nतुम तरस नहीं खाते बस्तियाँ जलाने में।',
'BASHIR_BADR','bashir badr,ghar,bastiyaan,dard',1,'Bashir Badr','1935–'),

('यहाँ सब लोग आते हैं, यहाँ सब लोग जाते हैं,\nबस यादें रह जाती हैं जब अपने छोड़ जाते हैं।',
'BASHIR_BADR','bashir badr,yaadein,jaana,apne',1,'Bashir Badr','1935–'),

('उजाले अपनी यादों के हमारे साथ रहने दो,\nन जाने किस गली में ज़िंदगी की शाम हो जाए।',
'BASHIR_BADR','bashir badr,ujale,yaad,shaam,zindagi',1,'Bashir Badr','1935–'),

('तुम्हारे शहर में बारिश हो या धूप,\nहमें हर मौसम तुम्हारी याद आती है।',
'BASHIR_BADR','bashir badr,sheher,baarish,yaad,mausam',1,'Bashir Badr','1935–'),

('सफ़र में धूप तो होगी जो चल सको तो चलो,\nसभी हैं भीड़ में तुम भी निकल सको तो चलो।',
'BASHIR_BADR','bashir badr,safar,dhoop,chalna',1,'Bashir Badr','1935–');

/* ═══════════════════════════════════════════════════════
   ZINDAGI / FALSAFA — New Category
   Life philosophy, deep soch, existential thoughts
   Mix of poets + general deep shayari
═══════════════════════════════════════════════════════ */
INSERT INTO shayaris (text, category, tags, is_featured, poet, poet_era) VALUES

-- Ghalib
('हम को मालूम है जन्नत की हक़ीक़त लेकिन,\nदिल के ख़ुश रखने को ग़ालिब ये ख़्याल अच्छा है।',
'ZINDAGI','ghalib,jannat,dil,khyal,falsafa',1,'Mirza Ghalib','1797–1869'),

('न था कुछ तो ख़ुदा था, कुछ न होता तो ख़ुदा होता,\nडुबोया मुझको होने ने, न होता मैं तो क्या होता।',
'ZINDAGI','ghalib,khuda,hona,wajood',1,'Mirza Ghalib','1797–1869'),

('हर एक बात पे कहते हो तुम कि तू क्या है,\nतुम्हीं कहो कि ये अंदाज़-ए-गुफ़्तगू क्या है।',
'ZINDAGI','ghalib,baat,andaaz,sawaal',1,'Mirza Ghalib','1797–1869'),

-- Faiz
('और भी दुःख हैं ज़माने में मोहब्बत के सिवा,\nराहतें और भी हैं वस्ल की राहत के सिवा।',
'ZINDAGI','faiz,dukh,zamana,rahat,zindagi',1,'Faiz Ahmed Faiz','1911–1984'),

-- Iqbal
('ख़ुदी को कर बुलंद इतना कि हर तक़दीर से पहले,\nख़ुदा बंदे से ख़ुद पूछे बता तेरी रज़ा क्या है।',
'ZINDAGI','iqbal,khudi,taqdeer,zindagi',1,'Allama Iqbal','1877–1938'),

('सितारों से आगे जहाँ और भी हैं,\nअभी इश्क़ के इम्तिहाँ और भी हैं।',
'ZINDAGI','iqbal,sitare,jahan,aage',1,'Allama Iqbal','1877–1938'),

-- John Elia
('मैं ख़ुद को ढूँढता रहा सारी उम्र,\nमिला तो बस एक अजनबी-सा शख्स।\nये ज़िंदगी का सबसे बड़ा सच है,\nकि हम को ख़ुद से ही नहीं पहचान।',
'ZINDAGI','john elia,dhundhna,ajnabi,sach,pehchaan',1,'John Elia','1931–2002'),

-- Gulzar
('कभी किसी को मुकम्मल जहाँ नहीं मिलता,\nकहीं ज़मीन तो कहीं आसमाँ नहीं मिलता।',
'ZINDAGI','gulzar,mukammal,jahan,zindagi',1,'Gulzar','1934–'),

('मोड़ पे रुक के मुड़ के देखा है,\nज़िंदगी तू कहाँ खड़ी थी अभी।',
'ZINDAGI','gulzar,zindagi,mod,waqt',1,'Gulzar','1934–'),

-- Rahat Indori
('घर से मस्जिद है बहुत दूर, चलो यूँ कर लें,\nकिसी रोते हुए बच्चे को हँसाया जाए।',
'ZINDAGI','rahat,insaaniyat,zindagi,khushi',1,'Rahat Indori','1950–2020'),

-- Sahir
('मैं पल दो पल का शायर हूँ,\nपल दो पल मेरी जवानी है।\nपल दो पल मेरी हस्ती है,\nपल दो पल मेरी कहानी है।',
'ZINDAGI','sahir,pal,waqt,zindagi,kahani',1,'Sahir Ludhianvi','1921–1980'),

-- Bashir Badr
('उजाले अपनी यादों के हमारे साथ रहने दो,\nन जाने किस गली में ज़िंदगी की शाम हो जाए।',
'ZINDAGI','bashir badr,ujale,yaad,zindagi,shaam',1,'Bashir Badr','1935–'),

('सफ़र में धूप तो होगी जो चल सको तो चलो,\nसभी हैं भीड़ में तुम भी निकल सको तो चलो।',
'ZINDAGI','bashir badr,safar,zindagi,chalna',1,'Bashir Badr','1935–'),

-- General deep Zindagi shayari (no specific poet)
('ज़िंदगी एक सफ़र है सुहाना,\nयहाँ कल क्या हो किसने जाना।\nजो मिला उसे दिल से लगा लो,\nजो चला गया उसे भूल जाओ।',
'ZINDAGI','zindagi,safar,kal,dil',1,NULL,NULL),

('वक़्त बड़ा बेरहम होता है,\nन किसी का दोस्त, न दुश्मन।\nबस चलता जाता है अपनी धुन में,\nरुकता नहीं किसी के लिए एक पल।',
'ZINDAGI','waqt,beraham,zindagi,pal',1,NULL,NULL),

('ज़िंदगी में दो चीज़ें कभी वापस नहीं आतीं —\nएक गया हुआ वक़्त,\nदूसरा कहा हुआ लफ़्ज़।\nइसलिए सोच समझ कर बोलो,\nऔर हर लम्हे को जी भर कर जियो।',
'ZINDAGI','zindagi,waqt,lafz,lamha,jiyo',1,NULL,NULL),

('जो लोग कहते हैं ज़िंदगी मुश्किल है,\nवो ज़िंदगी को जीते नहीं, बस काटते हैं।\nजो लोग ज़िंदगी को प्यार करते हैं,\nउनके लिए हर मुश्किल एक सबक़ बन जाती है।',
'ZINDAGI','zindagi,mushkil,pyaar,sabaq',1,NULL,NULL),

('हर शाम एक सवाल लेकर आती है —\nआज का दिन कैसा था?\nकुछ सीखा? कुछ दिया?\nकुछ जीया?\nया बस गुज़ार दिया।',
'ZINDAGI','shaam,sawaal,jeena,seekhna',1,NULL,NULL),

('ज़िंदगी का मतलब सिर्फ़ साँस लेना नहीं,\nज़िंदगी का मतलब है महसूस करना —\nहर दर्द को, हर ख़ुशी को,\nहर रिश्ते को, हर लम्हे को।',
'ZINDAGI','zindagi,mehsoos,dard,khushi,lamha',1,NULL,NULL);
