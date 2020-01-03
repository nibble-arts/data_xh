<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="xml"/>
	<xsl:param name="value"/>

	<!-- match all nodes -->
	<xsl:template match="@* | node()">
		<xsl:copy>
			<xsl:apply-templates select="@* | node()"/>
		</xsl:copy>
	</xsl:template>


	<!-- match the input nodes -->
	<xsl:template match="select">

		<div>
			<select>
				<option></option>
				<option>
					<xsl:value-of select="$value"/>
				</option>
			</select>
		</div>

	</xsl:template>

</xsl:stylesheet>

