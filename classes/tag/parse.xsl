<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="xml"/>


	<!-- match all nodes -->
	<xsl:template match="@* | node()">
		<xsl:copy>
			<xsl:apply-templates select="@* | node()"/>
		</xsl:copy>
	</xsl:template>


	<!-- ************************************* -->
	<!-- ************************************* -->
	<!-- match form nodes -->
	<!-- ************************************* -->
	<!-- ************************************* -->
<!-- 	<xsl:template match="form">

		<xsl:apply-templates select="@label"/>

		<form class="form_form">
			<xsl:copy-of select="."/>
		</form>

	</xsl:template> -->


	<!-- ************************************* -->
	<!-- ************************************* -->
	<!-- match input nodes -->
	<!-- ************************************* -->
	<!-- ************************************* -->
	<xsl:template match="input">

		<xsl:variable name="source" select="@source"/>

		<div class="form_line">
			<xsl:apply-templates select="@label"/>

			<input>
				<xsl:attribute name="type">
					<xsl:value-of select="'text'"/>
				</xsl:attribute>

				<xsl:attribute name="value">
					<xsl:value-of select="@source"/>
				</xsl:attribute>
			</input>
		</div>

	</xsl:template>


	<!-- ************************************* -->
	<!-- ************************************* -->
	<!-- match textarea nodes -->
	<!-- ************************************* -->
	<!-- ************************************* -->
	<xsl:template match="textarea">

		<div class="form_line">
			<xsl:apply-templates select="@label"/>

			<textarea>
				<xsl:attribute name="name">
					<xsl:value-of select="@name"/>
				</xsl:attribute>

				<xsl:value-of select="@source"/>
 				<xsl:text> </xsl:text>
			</textarea>
		</div>

	</xsl:template>


	<!-- ************************************* -->
	<!-- ************************************* -->
	<!-- match submit nodes -->
	<!-- ************************************* -->
	<!-- ************************************* -->
	<xsl:template match="submit">

		<div class="form_line">
			<input type="submit">
				<xsl:attribute name="name">
					<xsl:value-of select="@name"/>
				</xsl:attribute>

				<xsl:attribute name="value">
					<xsl:value-of select="@label"/>
				</xsl:attribute>
			</input>
		</div>

	</xsl:template>


	<!-- ************************************* -->
	<!-- ************************************* -->
	<!-- match select nodes -->
	<!-- ************************************* -->
	<!-- ************************************* -->
	<xsl:template match="select">

		<div class="form_line">
			<xsl:apply-templates select="@label"/>

			<select>
				<xsl:call-template name="add_option">
					<xsl:with-param name="data" select="@source"/>
				</xsl:call-template>
			</select>
		</div>

	</xsl:template>


	<!-- ************************************* -->
	<!-- split | separated values to option tags -->
	<xsl:template name="add_option">

		<xsl:param name="data"/>

		<xsl:variable name="string">
			<xsl:value-of select="substring-before($data,'|')"/>
		</xsl:variable>

		<xsl:variable name="rest">
			<xsl:value-of select="substring-after($data,'|')"/>
		</xsl:variable>


		<!-- add option -->
		<xsl:variable name="content">
			<xsl:choose>

				<!-- recursion -->
				<xsl:when test="$string != ''">
						<xsl:value-of select="$string"/>
				</xsl:when>

				<xsl:otherwise>
						<xsl:value-of select="$data"/>
				</xsl:otherwise>

			</xsl:choose>
		</xsl:variable>


		<!-- split content and value -->
		<xsl:variable name="val">
			<xsl:call-template name="get_val">
				<xsl:with-param name="data" select="$content"/>
			</xsl:call-template>
		</xsl:variable>

		<option>
			<xsl:if test="$val != ''">
				<xsl:attribute name="value">
					<xsl:value-of select="$val"/>
				</xsl:attribute>
			</xsl:if>

			<xsl:call-template name="get_cont">
				<xsl:with-param name="data" select="$content"/>
			</xsl:call-template>
		</option>


		<!-- recursion -->
		<xsl:if test="$rest != ''">

			<xsl:call-template name="add_option">
				<xsl:with-param name="data" select="$rest"/>
			</xsl:call-template>

		</xsl:if>
	</xsl:template>


	<!-- ************************************* -->
	<!-- get content from content@value -->
	<xsl:template name="get_cont">

		<xsl:param name="data"/>
		<xsl:value-of select="substring-before($data,'@')"/>

	</xsl:template>


	<!-- ************************************* -->
	<!-- get content from content@value -->
	<xsl:template name="get_val">

		<xsl:param name="data"/>
		<xsl:value-of select="substring-after($data,'@')"/>
		
	</xsl:template>


	<!-- ************************************* -->
	<!-- remove source attribute -->
	<xsl:template match="@source">
	</xsl:template>


	<!-- ************************************* -->
	<!-- add label -->
	<xsl:template match="@label">

		<div class="form_label">
			<xsl:value-of select="."/>
		</div>
	</xsl:template>

</xsl:stylesheet>

