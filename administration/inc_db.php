<script type="text/javascript">
var Db = {
	begin : function() {
		var html='<img src="../images/console.gif" style="border:0px;">';
		$("#console").append(html);
	},
	end : function(text) {
		$("#console img").remove();
		$("#console").append(text);
		$("#console").append(' >>><br>');
	},
	setUploadBtn : function() {
		if ($("#file").val()!='') {
			$("#uploadBtn").removeAttr('disabled');
		} else {
			$("#uploadBtn").attr('disabled','disabled');
		}
	},
	readCSVfiles : function(target) {
		this.begin();
		url= 'readdir.php?target='+target;
		$.ajax({
			url: url,
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				Db.end('"'+target+'" CSV filer genindlæst ..');
				$("#"+target+'-files').html(html);
				$("#"+target+'-files input').live("change", function() {
					if ($("#"+target).val()!='') {
						$("#"+target+'-btn').removeAttr('disabled');
					} else {
						$("#"+target+'-btn').attr('disabled','disabled');
					}
				});
			},
			error: function (xhr, ajaxOptions, thrownError){
	                    alert(xhr.status);
	                    alert(thrownError);
	                }  
		});
	},
	showTables : function() {
		this.begin();
		url= 'ajax_db.php?action=showtables';
		$.ajax({
			url: url,
			dataType : 'text',
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				Db.end('Liste af backups indlæst ...');
				$("#tables-cnt").html(html);
				$("#tables-cnt input").live("change", function() {
					if ($("#table").val()!='') {
						$("#table-btn").removeAttr('disabled');
					} else {
						$("#table-btn").attr('disabled','disabled');
					}
				});

			}
		});
	},
	createBackup: function() {
		this.begin();
		url= 'ajax_db.php?action=backup';
		$.ajax({
			url: url,
			dataType : 'text',
			cache: false,
			async: true,
			timeout : 50000,
			success: function(html) {
				Db.end(html);
			}
		});
	},
	updateDK: function() {
		this.begin();
		url= 'ajax_db.php?action=updateDK';
		$.ajax({
			url: url,
			dataType : 'text',
			cache: false,
			async: true,
			timeout : 50000,
			success: function(html) {
				Db.end(html);
			}
		});
	},
	updateCRLF: function() {
		this.begin();
		url= 'ajax_db.php?action=updateCRLF';
		$.ajax({
			url: url,
			dataType : 'text',
			cache: false,
			async: true,
			timeout : 50000,
			success: function(html) {
				Db.end(html);
			}
		});
	},
	restoreBackup: function() {
		this.begin();
		url= 'ajax_db.php?action=restoreBackup';
		url+='&table='+$("input[name=table]:checked").val();
		$.ajax({
			url: url,
			dataType : 'text',
			cache: false,
			async: true,
			timeout : 50000,
			success: function(html) {
				Db.end(html);
			}
		});
	},
	convertCSV : function() {
		this.begin();
		var url='convert.php?action=convert';
		url+='&file='+$("input[name=konv]:checked").val();
		//alert(url);
		$.ajax({
			url: url,
			dataType : 'text',
			cache: false,
			async: true,
			timeout : 500000,
			success: function(html) {
				Db.end(html);
			}
		});
	},
	testCSV: function() {
		this.begin();
		var url='convert.php?action=test';
		url+='&file='+$("input[name=test]:checked").val();
		$.ajax({
			url: url,
			dataType : 'text',
			cache: false,
			async: true,
			timeout : 500000,
			success: function(html) {
				Db.end(html);
			},
			error: function (xhr, ajaxOptions, thrownError){
	                    alert(xhr.status);
	                    alert(thrownError);
	                }  
		});
	}
		
}
$(document).ready(function() {
	Db.begin('');
	Db.end('');
	Db.readCSVfiles('konv');
	Db.readCSVfiles('test');
	Db.showTables();
});
</script>

<div style="float:left;clear:none;width:700px;">
<input type="button" value="Opret backup af allearter-databasen" onclick="Db.createBackup();"/>
<p id="backup-msg">Skab en kopi af databasen på serveren. 
Kopien kan f.eks benyttes til at genskabe en droppet eller ødelagt database efter en mislykket opdatering. 
</p>

<form action="upload.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="Db.begin();">
<input type="submit" id="uploadBtn" disabled="disabled" value="Upload en ny version af allearter-databasen"/><br/>
<input type="file" name="file" id="file" onchange="Db.setUploadBtn();"/> 
<p>Upload et nyt CSV-udtræk. Filen <u>skal være zipped</u> og vil blive lagt på serveren i et særligt katalog.
Når filen er uploaded vil den blive udpakket straks.
</p>
</form>
<iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>  

<input type="button" id="test-btn" value="Test CSV data integritet" disabled="disabled" onclick="Db.testCSV();"/>
<div id="test-files"></div>
<p>Test indholdet i en given uploaded, udpakket CSV. Der testes om indholdet faktisk er en læsbar CSV, 
samt hvorvidt felterne / kolonnerne svarer til dem, som konverteringen forventer eksisterer.</p>

<input type="button" id="konv-btn" value="Konverter CSV til allearter-databasen (opdatering)" disabled="disabled" onclick="Db.convertCSV();"/>
<div id="konv-files"></div>
<p>Vælg en uploaded, udpakket CSV-fil fra upload-kataloget, og forsøg at konvertere indholdet fra denne over i allearter-databasen.</p>

<input type="button" value="Opdater DK-navne" onclick="Db.updateDK();"/>
<p>Opdaterer <i>samtlige</i> *_dk-felter i den aktuelle allearter-database med videnskabelige navne, i fald de er tomme. 
</p>

<input type="button" value="Fjern linieskift i klassifikations-kategorier" onclick="Db.updateCRLF();"/>
<p>Fjerner linieskift / "bløde" linieskift i <i>samtlige</i> klassifikations-kategorier. 
</p>

<input type="button" value="Genskab database ud fra backup" id="table-btn" disabled="disabled" onclick="Db.restoreBackup();"/>
<div id="tables-cnt"></div>
<p>Genskab allearter-databasen ud fra en backup.</p>

<br><br><br><br>
</div>

<div style="float:left;clear:none;">
<h3>Beskeder fra serveren / scripts</h3>
<div class="console" id="console">
</div>
</div>

