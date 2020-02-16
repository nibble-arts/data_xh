<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="html"/>

	<xsl:param name="uri"/>
	<xsl:param name="form"/>
	<xsl:param name="prefix"/>
	<xsl:param name="return"/>


	<!-- match all nodes -->
	<xsl:template match="/">

		<script type="text/javascript" src="plugins/form/script/form.js"/>

		<form method="post" class="data_form">
			<xsl:attribute name="action">
				<xsl:value-of select="$return"/>
			</xsl:attribute>

			<xsl:apply-templates select="//data/*"/>
		</form>

	</xsl:template>



	<xsl:template match="data/*">

		<xsl:param name="pos" select="position()"/>
		<xsl:variable name="id" select="id"/>
		<xsl:variable name="data" select="."/>

		<div class="data_bewertung_page">

			<div class="data_bewertung_title">
				<div class="title">Landesmeisterschaft 2020 - Region <xsl:value-of select="region"/></div>
				<div class="text">Bewertungsbogen für Juroren</div>
			</div>

			<div class="data_bewertung_film">
				<div class="label">Titel</div>
				<div class="subtitle"><xsl:value-of select="title"/></div>
			</div>

			<div class="data_bewertung_laufzeit">
				<div class="label">Laufzeit</div>
				<div class="text"><xsl:value-of select="time"/> Minuten</div>
			</div>

			<div class="data_bewertung_autor">
				<div class="label">Autor</div>
				<div class="text"><xsl:value-of select="author"/></div>
			</div>

			<div class="data_bewertung_abstract">
				<div class="label">Kurzinhalt</div>
				<div class="text"><xsl:value-of select="abstract"/></div>
			</div>

			<div class="data_bewertung_subtitle">
				<div class="subtitle">Raum für Bemerkungen</div>
			</div>

			<div class="data_bewertung_notes">
			</div>

			<div class="data_bewertung_noten">
				<div class="label">Note</div>
			</div>

			<div class="data_bewertung_stm">
				<div class="label">Zur StM</div>
			</div>

			<div class="data_bewertung_sonderpreis">
				<div class="label">Sonderpreise</div>
			</div>

		</div>

	</xsl:template>

</xsl:stylesheet>