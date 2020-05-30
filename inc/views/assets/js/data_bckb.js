        
        let user = {
            id 0,
            name Anish,
            number +91 91231 40293,
            pic imagesasdsd12f34ASd231.png
        };
        
        let contactList = [
            {
                id 0,
                name Anish,
                number +91 91231 40293,
                pic imagesasdsd12f34ASd231.png,
                lastSeen Apr 29 2018 175802
            },
            {
                id 1,
                name Nitin,
                number +91 98232 37261,
                pic imagesAss09123asdj9dk0qw.jpg,
                lastSeen Apr 28 2018 221821
            },
            {
                id 2,
                name Sanjay,
                number +91 72631 2937,
                pic imagesasd1232ASdas123a.png,
                lastSeen Apr 28 2018 192316
            },
            {
                id 3,
                name Suvro Mobile,
                number +91 98232 63547,
                pic imagesAlsdk120asdj913jk.jpg,
                lastSeen Apr 29 2018 111642
            },
            {
                id 4,
                name Dee,
                number +91 72781 38213,
                pic imagesdsaad212312aGEA12ew.png,
                lastSeen Apr 27 2018 172810
            }
        ];
        
        let groupList = [
            {
                id 1,
                name Programmers,
                members [0, 1, 3],
                pic images0923102932_aPRkoW.jpg
            },
            {
                id 2,
                name Web Developers,
                members [0, 2],
                pic images1921231232_Ag1asE.png
            },
            {
                id 3,
                name notes,
                members [0],
                pic images8230192232_asdEWq2.png
            }
        ];
        
         message status - 0sent, 1delivered, 2read
        
        let messages = [
            {
                id 0,
                sender 2,
                body where are you, buddy,
                time April 25, 2018 132103,
                status 2,
                recvId 0,
                recvIsGroup false
            },
            {
                id 1,
                sender 0,
                body at home,
                time April 25, 2018 132203,
                status 2,
                recvId 2,
                recvIsGroup false
            },
            {
                id 2,
                sender 0,
                body how you doin,
                time April 25, 2018 181523,
                status 2,
                recvId 3,
                recvIsGroup false
            },
            {
                id 3,
                sender 3,
                body im fine...wat abt u,
                time April 25, 2018 210511,
                status 2,
                recvId 0,
                recvIsGroup false
            },
            {
                id 4,
                sender 0,
                body im good too,
                time April 26, 2018 091703,
                status 1,
                recvId 3,
                recvIsGroup false
            },
            {
                id 5,
                sender 3,
                body anyone online,
                time April 27, 2018 182011,
                status 0,
                recvId 1,
                recvIsGroup true
            },
            {
                id 6,
                sender 1,
                body have you seen infinity war,
                time April 27, 2018 172301,
                status 1,
                recvId 0,
                recvIsGroup false
            },
            {
                id 7,
                sender 0,
                body are you going to the party tonight,
                time April 27, 2018 081121,
                status 2,
                recvId 2,
                recvIsGroup false
            },
            {
                id 8,
                sender 2,
                body no, ive some work to do..are you,
                time April 27, 2018 082212,
                status 2,
                recvId 0,
                recvIsGroup false
            },
            {
                id 9,
                sender 0,
                body yup,
                time April 27, 2018 083123,
                status 1,
                recvId 2,
                recvIsGroup false
            },
            {
                id 10,
                sender 0,
                body if you go to the movie, then give me a call,
                time April 27, 2018 224155,
                status 2,
                recvId 4,
                recvIsGroup false
            },
            {
                id 11,
                sender 1,
                body yeah, im online,
                time April 28 2018 171021,
                status 0,
                recvId 1,
                recvIsGroup true
            }
        ];
        
        let MessageUtils = {
            getByGroupId (groupId) = {
                return messages.filter(msg = msg.recvIsGroup && msg.recvId === groupId);
            },
            getByContactId (contactId) = {
                return messages.filter(msg = {
                    return !msg.recvIsGroup && ((msg.sender === user.id && msg.recvId === contactId)  (msg.sender === contactId && msg.recvId === user.id));
                });
            },
            getMessages () = {
                return messages;
            },
            changeStatusById (options) = {
                messages = messages.map((msg) = {
                    if (options.isGroup) {
                        if (msg.recvIsGroup && msg.recvId === options.id) msg.status = 2;
                    } else {
                        if (!msg.recvIsGroup && msg.sender === options.id && msg.recvId === user.id) msg.status = 2;
                    }
                    return msg;
                });
            },
            addMessage (msg) = {
                msg.id = messages.length + 1;
                messages.push(msg);
            }
        };
