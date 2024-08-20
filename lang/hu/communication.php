<?php

return [

    /**
     * E-mail texts
     */

    // email-change-request
    'EmailChangeRequestMailSubject' => 'E-mail címe megváltoztatását kérték',
    'EmailChangeRequestMailBodyLine1' => ucfirst(':FromCurrentSystemName').' e-mail cím módosítási kérelmet kaptunk.',
    'EmailChangeRequestMailBodyLine2' => 'Kattintson az alábbi gombra ahhoz, hogy igazolja, hogy a kérés Öntől érkezett.',
    'EmailChangeRequestMailBodyLineButtonText' => 'Igazolom, hogy az új e-mail cím hozzám tartozik, és kérem, hogy innentől ez legyen a kapcsolattartó e-mail címem.',
    'EmailChangeRequestMailBodyLine3' => 'Ha nem az Ön kérése során generálódott ez a levél, akkor semmi teendője vele. Más nem tud visszaélni az Ön e-mail címével ebben a rendszerben.',
    'EmailChangeRequestMailBodyLine4' => 'Ez egy automatikus üzenet :FromCurrentSystemName, válaszolni nem szükésges rá.',

    // reset-password
    'ResetPasswordMailSubject' => 'Jelszó-helyreállítási igény érkezett',
    'ResetPasswordMailTitle' => 'Jelszó-helyreállítás',
    'ResetPasswordMailLine1' => ucfirst(':FromCurrentSystemName').' jelszóhelyreállítási kérelmet kaptunk.',
    'ResetPasswordMailLine2' => 'Kattintson az alábbi gombra ahhoz, hogy igazolja, hogy a kérés Öntől érkezett.',
    'ResetPasswordMailButtonText' => 'Új jelszót akarok',
    'ResetPasswordMailLine3' => 'Ha nem az Ön kérése során generálódott ez a levél, akkor semmi teendője vele. Más nem tud visszaélni az Ön e-mail címével ebben a rendszerben.',
    'ResetPasswordMailLine4' => 'Ez egy automatikus üzenet :FromCurrentSystemName, válaszolni nem szükésges rá.',

    // verify-registration
    'VerifyRegistrationSubject' => 'A regisztráció megerősítése',
    'VerifyRegistrationLine1' => 'Kattintson a lentebbi gombra a regisztráció megerősítéséhez.',
    'VerifyRegistrationLine2' => 'Amennyiben a regisztrációt nem Ön kezdeményezte, kérjük, ezt a levelet tekintse tárgytalannak.',

    /**
     * General texts
     */
    'EmailDispatchProcessList' => 'E-mail kiküldések listája',
    'EmailDispatchList' => 'Kiküldött e-mailek listája',
    'EmailDispatchView' => 'Kiküldött e-mail megtekintése',

    'Communication' => 'Kommunikáció',
    'EmailDispatchProcesses' => 'Kötegelt e-mail kiküldések',
    'EmailDispatches' => 'Kiküldött e-mailek',
    'CustomerCommunicationInterface' => 'Kommunikáció',
    'CountDispatches' => 'Üzenetek száma',
    'SenderEmailAddress' => 'Feladó e-mail címe',
    'SenderName' => 'Feladó neve',
    'RecipientEmailAddress' => 'Címzett e-mail címe',
    'RecipientName' => 'Címzett neve',
    'ReferenceCode' => 'Hivatkozás',
    'Subject' => 'Tárgy',
    'EmailBody' => 'Szövegtörzs',
    'SentAt' => 'Küldés dátuma',

];
