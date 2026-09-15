Create a livewire component ExamMarksEntryComp.php with its corresponding view with project look and feel and place in main menu in the exam section. As follows
create a table for each Shreny-Section combination found using @ShrenySection.php model with heading Shreny Name & Section Name for the current active session. Now for each allotted subjects that has been taken for examination should be listed in the first column according to subject_type_id of the table should found using model @ExamShrenyPartFmPm.php and @Subject.php then each ExamCombination should be place as a column and for each subject there must be a link to open a new page for the selected Shreny and Section’s as Studentcrs with a textbox for entering marks that should be saved in database table using model @ExamMarksEntry. There should be a check box embedded to textbox, if it checked it shows AB and -99 should be saved in table. If is_finalised is set to true it should show as finalized, and if is_issue is active it must show that also a unfinalized button, that is_finalized set to false  
When AB is checked, text box should be disabled & show AB in red. If it unchecked textbox should be editable again. 
Teacher name and is_finalized, is_issue option should show, at the marks entry point when to click on button
All others Exam Combination for the selected subjects marks should show in data mode only beside current one


Create a ExamMarksRegister for Each Shreny and Shreny-Section combination with all ExamCombination for each studentcrs should be found using model @ExamMarksEntry.php, in compact and classic view, Full marks should show at the top if there is no change in full marks, that should be found using model @ExamShrenyPartFmPm.php, each ExamTerm show the a column total of all its exam parts. At last columns the overall Total for each subject should show at the end with grade 
there should be an option to download markregister as pdf also 
Now Create a ExamMarksSheet for each studentcrs according to Shreny-Section combination with all ExamCombination. 
First School Name, Logo, Address etc placed top of the page with bigger font, then session and “Final Progress Report” 
Then Student Details, current Shreny, Section, Roll No from @StudentCr.php model, and other detail like Father Name, Dob, Address from corresponding @StudentDb.php 
Following should be filled using a table, All Subjects for the selected Shreny, found from ShrenySubject.php model should place in first column according to subject_type_id then subsequent columns and Sub-columns should hold ExamCombinations with total for each, final Total & Grade for each subject should place at the end column.
over total marks and grade should be placed at the bottom of the page in bold form
in lower part of the page Seven Point Grade System should be shown and Teacher remarks and signature for each exam term should be placed
at the end of the page Signature of gudian, Shreny teacher & head teacher should be place with appropriate space above
the above should be shown in pdf also
