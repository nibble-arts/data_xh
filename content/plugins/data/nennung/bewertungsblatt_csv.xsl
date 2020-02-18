<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="text"/>

	<xsl:param name="uri"/>
	<xsl:param name="form"/>
	<xsl:param name="prefix"/>
	<xsl:param name="return"/>


	<!-- match all nodes -->
	<xsl:template match="/">

		<xsl:text>Nr.;Titel;Author;Klub;Zeit;J1;J2;J3;J4;J5;Endw.;>StM;Sonderpreise;Notiz</xsl:text>
		<xsl:text>&#xa;</xsl:text>

		<xsl:apply-templates select="//data/*"/>

	</xsl:template>

	<xsl:template match="data/*">

		<xsl:text>"</xsl:text>
		<xsl:value-of select="position()"/>
		<xsl:text>";"</xsl:text>

		<xsl:value-of select="title"/>
		<xsl:text>";"</xsl:text>

		<xsl:value-of select="author"/>
		<xsl:text>";"</xsl:text>

		<xsl:value-of select="klub"/>
		<xsl:text>";"</xsl:text>

		<xsl:value-of select="time"/>
		<xsl:text>";</xsl:text>

		<xsl:text>;;;;;;;;</xsl:text>

		<xsl:text>&#xa;</xsl:text>

	</xsl:template>

</xsl:stylesheet>